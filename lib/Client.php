<?php

namespace CoinGate;

use CoinGate\Services\AbstractService;
use CoinGate\Services\ServiceFactory;

/**
 * Client used to send requests to CoinGate's API
 */
class Client extends BaseClient
{
    /**
     * @var ServiceFactory
     */
    private $factory;

    /**
     * @param string $name
     * @return AbstractService|null
     */
    public function __get(string $name)
    {
        if ($this->factory === null) {
            $this->factory = new ServiceFactory($this);
        }

        return $this->factory->__get($name);
    }
}
