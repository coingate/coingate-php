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
     * @var array
     */
    private $services;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->services = [];
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    abstract protected function getServiceClass(string $name): ?string;

    /**
     * @param string $name
     *
     * @return AbstractService|null
     */
    public function __get(string $name): ?AbstractService
    {
        if (($serviceClass = $this->getServiceClass($name)) !== null) {

            if (! array_key_exists($name, $this->services)) {
                $this->services[$name] = new $serviceClass($this->client);
            }

            return $this->services[$name];
        }

        trigger_error('Undefined property: ' . static::class . '::$' . $name);

        return null;
    }
}