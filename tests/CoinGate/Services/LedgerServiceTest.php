<?php

namespace CoinGate\Services;

use CoinGate\TestCase;

class LedgerServiceTest extends TestCase
{
    private $mockHttpClient;

    /** @var \CoinGate\Services\LedgerService */
    private $service;

    /**
     * @before
     */
    protected function setUpService()
    {
        $this->mockHttpClient = $this->mockHttpClient();

        $client = $this->createSandboxClient($this->mockHttpClient);

        $this->service = new LedgerService($client);
    }

    public function testGetLedgerAccount()
    {
        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'id' => '01G0EM0RRVREB4WD5KDB6VJVPM'
                ]),
                200
            ]);

        $account = $this->service->get('01G0EM0RRVREB4WD5KDB6VJVPM');

        $this->assertObjectHasAttribute('id', $account);
    }

    public function testNotFoundGetLedgerAccount()
    {
        $this->mockHttpClient
            ->method('request')
            ->willThrowException(
                \CoinGate\Exception\Api\LedgerAccountNotFound::factory('Ledger Account not found', 404)
            );

        $this->expectException(\CoinGate\Exception\Api\LedgerAccountNotFound::class);

        $this->service->get('01G0EM0RRVREB4WD5KDB6VJVPM');
    }

    public function testListLedgerAccounts()
    {
        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'current_page' => 1,
                    'per_page' => 20,
                    'total_accounts' => 1,
                    'total_pages' => 1,
                    'accounts' => [
                        [ 'id' => '01G0EM0RRVREB4WD5KDB6VJVPM' ]
                    ]
                ]),
                200
            ]);

        $response = $this->service->list();

        $this->assertNotEmpty($response);
    }
}
