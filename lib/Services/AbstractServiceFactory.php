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
}
