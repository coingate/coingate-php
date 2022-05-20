<?php

namespace CoinGate;

use CoinGate\Services\AbstractService;
use CoinGate\Services\PublicService;
use CoinGate\Services\ServiceFactory;

/**
 * Client used to send requests to CoinGate's API
 */
class Client extends BaseClient
{
    /**
     * @var ServiceFactory
     */
    protected $factory;

    /**
     * @param mixed $apiKey
     * @param bool $useSandboxEnv
     */
    public function __construct($apiKey = null, bool $useSandboxEnv = false)
    {
        parent::__construct($apiKey, $useSandboxEnv);

        $this->factory = new ServiceFactory($this);
    }

    /**
     * @param string $name
     * @return AbstractService|null
     */
    public function __get(string $name)
    {
        return $this->factory->__get($name);
    }

    /**
     * @param string $name
     * @param array<int,mixed> $arguments
     * @return PublicService|null
     */
    public function __call(string $name, array $arguments)
    {
        return $this->factory->__call($name, $arguments);
    }
}
