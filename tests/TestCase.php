<?php

namespace CoinGate;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public const APIKEY = '-Lj8CYksfaJw_VwoCPMEPUKiLDnFFGKz7szNKXYp';

    protected function createSandboxClient(): Client
    {
        return new Client(self::APIKEY, true);
    }
}
