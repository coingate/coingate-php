<?php

namespace CoinGate\Services;

/**
 * Service factory class for API resources in the root namespace.
 *
 * @property OrderService $service
 */

class ServiceFactory extends AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'order' => OrderService::class
    ];

    /**
     * @param  string $name
     * @return string|null
     */
    protected function getServiceClass(string $name): ?string
    {
        return self::$classMap[$name] ?: null;
    }
}
