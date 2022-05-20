<?php

namespace CoinGate\Services;

use CoinGate\ClientInterface;

abstract class AbstractServiceFactory
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var array<string, AbstractService>
     */
    private $services;

    /**
     * @var PublicService
     */
    private $publicService;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->services = [];

        // initialize public service
        $this->publicService = new PublicService($client);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    abstract protected function getServiceClass(string $name): ?string;

    /**
     * @param string $name
     * @return AbstractService|null
     */
    public function __get(string $name)
    {
        $serviceClass = $this->getServiceClass($name);

        if ($serviceClass === null) {
            trigger_error('Undefined property: ' . static::class . '::$' . $name);
            return null;
        }

        if (! isset($this->services[$name])) {
            $object = new $serviceClass($this->client);

            if (! ($object instanceof AbstractService)) {
                trigger_error('Undefined property: ' . static::class . '::$' . $name);
                return null;
            }

            $this->services[$name] = $object;
        }

        return $this->services[$name];
    }

    /**
     * @param string $name
     * @param array<int, mixed> $arguments
     * @return PublicService|null
     */
    public function __call(string $name, array $arguments)
    {
        if (! method_exists($this->publicService, $name)) {
            trigger_error('Call to undefined method ' . static::class . '::' . $name . '()');
            return null;
        }

        return $this->publicService->{$name}(...$arguments);
    }
}
