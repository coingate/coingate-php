<?php

namespace CoinGate;

/**
 * @internal
 * @covers \CoinGate\BaseClient
 */
class BaseClientTest extends TestCase
{
    public function testCtorThrowsIfApiKeyIsUnexpectedType()
    {
        $this->expectException(\CoinGate\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('api_key must be null or a string');

        $client = new BaseClient(234);
    }

    public function testCtorThrowsIfApiKeyIsEmpty()
    {
        $this->expectException(\CoinGate\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('api_key cannot be empty string');

        $client = new BaseClient('');
    }

    public function testCtorThrowsIfApiKeyContainsWhitespace()
    {
        $this->expectException(\CoinGate\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('api_key cannot contain whitespace');

        $client = new BaseClient(self::APIKEY . "\n");
    }

    public function testRequestThrowsIfInvalidApiKeyUsed()
    {
        $this->expectException(\CoinGate\Exception\Api\BadAuthToken::class);

        $client = new BaseClient('123456789', true);

        $client->request('get', '/v2/orders/1');
    }

    public function testPublicRequestWithEmptyApiKey()
    {
        $client = new BaseClient();

        $response = $client->request('get', '/v2/ping');

        $this->assertObjectHasAttribute('ping', $response);
    }

    public function testApiKeyAssignment()
    {
        $client = new BaseClient();

        $client->setApiKey(self::APIKEY);

        $this->assertEquals($client->getApiKey(), self::APIKEY);
    }
}
