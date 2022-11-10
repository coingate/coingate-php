<?php

namespace CoinGate;

use CoinGate\HttpClient\CurlClient;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public const APIKEY = '-Lj8CYksfaJw_VwoCPMEPUKiLDnFFGKz7szNKXYp';

    protected function mockHttpClient()
    {
        return $this->createMock(CurlClient::class);
    }

    protected function createSandboxClient($httpClient = null): Client
    {
        if ($httpClient !== null) {
            Client::setHttpClient($httpClient);
        }

        return new Client(self::APIKEY, true);
    }
}
