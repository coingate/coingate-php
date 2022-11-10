<?php

namespace CoinGate\Services;

use CoinGate\TestCase;

class WithdrawalServiceTest extends TestCase
{
    private $mockHttpClient;

    /** @var \CoinGate\Services\WithdrawalService */
    private $service;

    /**
     * @before
     */
    protected function setUpService()
    {
        $this->mockHttpClient = $this->mockHttpClient();

        $client = $this->createSandboxClient($this->mockHttpClient);

        $this->service = new WithdrawalService($client);
    }

    public function testGetWithdrawal()
    {
        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'id' => '10'
                ]),
                200
            ]);

        $withdrawal = $this->service->get(10);

        $this->assertObjectHasAttribute('id', $withdrawal);
    }

    public function testNotFoundWithdrawal()
    {
        $this->mockHttpClient
            ->method('request')
            ->willThrowException(
                \CoinGate\Exception\Api\WithdrawalNotFound::factory('Withdrawal does not exist', 404)
            );

        $this->expectException(\CoinGate\Exception\Api\WithdrawalNotFound::class);

        $this->service->get(10);
    }

    public function testWithdrawals()
    {
        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'current_page' => 1,
                    'per_page' => 20,
                    'total_withdrawal' => 1,
                    'total_pages' => 1,
                    'withdrawals' => [
                        [ 'id' => 1 ]
                    ]
                ]),
                200
            ]);

        $response = $this->service->list();

        $this->assertNotEmpty($response);
    }
}
