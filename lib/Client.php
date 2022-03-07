<?php

namespace CoinGate;

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

    public function __get($name)
    {
        if ($this->factory === null) {
            $this->factory = new ServiceFactory($this);
        }

        return $this->factory->__get($name);
    }
}
