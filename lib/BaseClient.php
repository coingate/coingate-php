<?php

namespace CoinGate;

use CoinGate\Exception\Api\BadAuthToken;
use CoinGate\Exception\Api\BadRequest;
use CoinGate\Exception\Api\NotFound;
use CoinGate\Exception\Api\OrderIsNotValid;
use CoinGate\Exception\Api\OrderNotFound;
use CoinGate\Exception\Api\Unauthorized;
use CoinGate\Exception\Api\UnprocessableEntity;
use CoinGate\Exception\ApiErrorException;
use CoinGate\Exception\InvalidArgumentException;
use CoinGate\Exception\InternalServerError;
use CoinGate\Exception\RateLimitException;
use CoinGate\Exception\UnknownApiErrorException;
use CoinGate\HttpClient\ClientInterface as HttpClientInterface;
use CoinGate\HttpClient\CurlClient;
use CoinGate\Services\OrderService;
use CoinGate\Services\PublicService;
use Exception;

/**
 * Client used to send requests to CoinGate's API
 *
 * @property OrderService $order
 * @mixin PublicService
 */
class BaseClient implements ClientInterface
{
    /**
     * @var string
     */
    public const VERSION = '4.1.0';

    /**
     * @var string default base URL for CoinBase's API
     */
    public const DEFAULT_API_BASE = 'https://api.coingate.com';

    /**
     * @var string default base URL for CoinBase's API
     */
    public const SANDBOX_DEFAULT_API_BASE = 'https://api-sandbox.coingate.com';

    /**
     * @var HttpClientInterface|null
     */
    protected static $httpClient = null;

    /**
     * @var array<string, string>|null
     */
    protected static $appInfo = null;

    /**
     * @var array<string, mixed>
     */
    private $config;

    /**
     * Initializes a new instance of CoinGate's BaseClient class.
     *
     * The constructor takes a single argument. The argument can be a string, in which case it
     * should be the API key. It can also be an array with various configuration settings.
     *
     * @param mixed $apiKey
     * @param bool|false $useSandboxEnv
     */
    public function __construct($apiKey = null, bool $useSandboxEnv = false)
    {
        $config = array_merge(
            $this->getDefaultConfig(),
            [
                'api_key' => $apiKey,
                'environment' => ! $useSandboxEnv ? 'live' : 'sandbox'
            ]
        );

        $this->validateConfig($config);

        // check if trying to connect to sandbox environment
        if ($useSandboxEnv) {
            $config['api_base'] = self::SANDBOX_DEFAULT_API_BASE;
        }

        $this->config = $config;
    }

    /**
     * Gets the API key used by the client to send requests.
     *
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->config['api_key'];
    }

    /**
     * @param string|null $apiKey
     * @return $this
     */
    public function setApiKey(string $apiKey = null): BaseClient
    {
        $this->config['api_key'] = $apiKey;
        $this->validateConfig($this->config);

        return $this;
    }

    /**
     * Gets the base URL for CoinGate's API.
     *
     * @return string
     */
    public function getApiBase(): string
    {
        return $this->config['api_base'];
    }

    /**
     * Gets the environment used to interact with CoinGate's API.
     *
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->config['environment'];
    }

    /**
     * @param string $environment
     * @return $this
     */
    public function setEnvironment(string $environment): BaseClient
    {
        $this->config['environment'] = $environment;
        $this->validateConfig($this->config);

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    private function getDefaultConfig(): array
    {
        return [
            'api_key' => null,
            'api_base' => self::DEFAULT_API_BASE,
            'environment' => 'live'
        ];
    }

    /**
     * @param array<string, mixed> $config
     * @return void
     */
    private function validateConfig(array $config = [])
    {
        if ($config['api_key'] !== null) {
            if (! is_string($config['api_key'])) {
                throw new InvalidArgumentException('api_key must be null or a string');
            }

            if (empty($config['api_key'])) {
                throw new InvalidArgumentException('api_key cannot be empty string');
            }

            if (preg_match('/\s/', $config['api_key'])) {
                throw new InvalidArgumentException('api_key cannot contain whitespace');
            }
        }

        // api_base
        if (! is_string($config['api_base'])) {
            throw new InvalidArgumentException('api_base must be a string');
        }

        // environment
        if (! in_array($config['environment'], ['live', 'sandbox'])) {
            throw new InvalidArgumentException('Environment does not exist. Available environments: live, sandbox.');
        }

        // check absence of extra keys
        $extraConfigKeys = array_diff(array_keys($config), array_keys($this->getDefaultConfig()));

        if (! empty($extraConfigKeys)) {
            $invalidKeys = "'" . implode("', '", $extraConfigKeys) . "'";

            throw new InvalidArgumentException('Found unknown key(s) in configuration array: ' . $invalidKeys);
        }
    }

    /**
     * @param  string $method
     * @return string[]
     */
    protected function getDefaultHeaders(string $method): array
    {
        $headers = [];

        if (($apiKey = $this->getApiKey()) !== null) {
            $headers[] = 'Authorization: Bearer ' . $apiKey;
        }

        if (in_array(strtolower($method), ['post', 'patch'])) {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }

        if (($appInfo = self::getAppInfo()) !== null) {
            $headers[] = 'User-Agent: CoinGate/v2 (PHP Library v' . self::VERSION . ', '
                . $appInfo['name'] . (! empty($appInfo['version']) ? ' v' . $appInfo['version'] : '') . ')';
        } else {
            $headers[] = 'User-Agent: CoinGate/v2 (PHP Library v' . self::VERSION . ')';
        }

        return $headers;
    }

    /**
     * @param  mixed $apiKey
     * @param  bool  $useSandboxEnv
     * @return bool
     */
    public static function testConnection($apiKey, bool $useSandboxEnv = false): bool
    {
        $client = new self($apiKey, $useSandboxEnv);

        try {
            $client->request('get', '/v2/auth/test');

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Send a request to CoinGate API.
     *
     * @param string $method the HTTP method
     * @param string $path   the path of the request
     * @param array<string, string> $params the parameters of the request
     *
     * @throws ApiErrorException
     */
    public function request(string $method, string $path, array $params = [])
    {
        // generate default headers
        $headers = $this->getDefaultHeaders($method);
        // generate absolute url
        $absUrl = $this->getApiBase() . '/' . trim($path, '/');

        [$responseBody, $httpStatus] = $this->getHttpClient()->request($method, $absUrl, $headers, $params);

        $responseFormatted = json_decode($responseBody, true) ?: $responseBody;

        if ($httpStatus !== 200) {
            $this->handleErrorResponse($responseFormatted, $httpStatus);
        }

        return is_array($responseFormatted)
            ? (object)$responseFormatted
            : $responseFormatted;
    }

    /**
     * @throws ApiErrorException
     */

    /**
     * @param mixed $response
     * @param int $httpStatus
     * @return void
     *
     * @throws ApiErrorException
     */
    public function handleErrorResponse($response, int $httpStatus)
    {
        $reason = $response['reason'] ?? null;

        if ($httpStatus === 400) {
            throw BadRequest::factory($response, $httpStatus);
        } elseif ($httpStatus === 401) {
            switch ($reason) {
                case 'BadAuthToken':
                    throw BadAuthToken::factory($response, $httpStatus);

                default:
                    throw Unauthorized::factory($response, $httpStatus);
            }
        } elseif ($httpStatus === 404) {
            switch ($reason) {
                case 'OrderNotFound':
                    throw OrderNotFound::factory($response, $httpStatus);

                default:
                    throw NotFound::factory($response, $httpStatus);
            }
        } elseif ($httpStatus === 422) {
            switch ($reason) {
                case 'OrderIsNotValid':
                    throw OrderIsNotValid::factory($response, $httpStatus);

                case 'OrderNotFound':
                    throw OrderNotFound::factory($response, $httpStatus);

                default:
                    throw UnprocessableEntity::factory($response, $httpStatus);
            }
        } elseif ($httpStatus === 429) {
            throw RateLimitException::factory($response, $httpStatus);
        } elseif (in_array($httpStatus, [500, 504])) {
            throw InternalServerError::factory($response, $httpStatus);
        }

        throw UnknownApiErrorException::factory($response, $httpStatus);
    }

    /**
     * @param HttpClientInterface $httpClient
     * @return void
     */
    public static function setHttpClient(HttpClientInterface $httpClient)
    {
        self::$httpClient = $httpClient;
    }

    /**
     * @return HttpClientInterface
     */
    protected function getHttpClient(): HttpClientInterface
    {
        if (! self::$httpClient) {
            self::$httpClient = CurlClient::instance();
        }

        return self::$httpClient;
    }

    /**
     * @return array<string, string>|null The application's information
     */
    public static function getAppInfo(): ?array
    {
        return self::$appInfo;
    }

    /**
     * @param string $appName The application's name
     * @param null|string $appVersion The application's version
     */
    public static function setAppInfo(string $appName, string $appVersion = null): void
    {
        self::$appInfo = [];
        self::$appInfo['name'] = trim($appName);
        self::$appInfo['version'] = trim($appVersion);
    }
}
