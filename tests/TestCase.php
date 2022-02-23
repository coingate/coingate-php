<?php

namespace CoinGate;

class TestCase extends \PHPUnit\Framework\TestCase
{
    const APIKEY = 'dNcas65sfp6kYe9BJftmSkZiuD8Fwqas_Aj5PQwu';

    protected function createSandboxClient()
    {
        return new Client(self::APIKEY, true);
    }
}