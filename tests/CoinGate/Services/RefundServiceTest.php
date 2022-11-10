<?php

namespace CoinGate\Services;

use CoinGate\TestCase;

class RefundServiceTest extends TestCase
{
    private $orderId = 1;

    private $mockHttpClient;

    /** @var \CoinGate\Services\RefundService */
    private $service;

    /**
     * @before
     */
    protected function setUpService()
    {
        $this->mockHttpClient = $this->mockHttpClient();

        $client = $this->createSandboxClient($this->mockHttpClient);

        $this->service = new RefundService($client);
    }

    public function testCreateOrderRefund()
    {
        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'id' => 1
                ]),
                200
            ]);

        $refund = $this->service->create($this->orderId, []);

        $this->assertObjectHasAttribute('id', $refund);

        return $refund;
    }

    public function testInvalidCreateRefundOrder()
    {
        $this->mockHttpClient
            ->method('request')
            ->willThrowException(
                \CoinGate\Exception\Api\RefundIsNotValid::factory('Refund is not valid', 422)
            );

        $this->expectException(\CoinGate\Exception\Api\RefundIsNotValid::class);

        $this->service->create($this->orderId, []);
    }

    /**
     * @depends testCreateOrderRefund
     */
    public function testGetOrderRefund($refund)
    {
        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'id' => $refund->id
                ]),
                200
            ]);

        $response = $this->service->get($this->orderId, $refund->id);

        $this->assertSame($refund->id, $response->id);
    }

    public function testNotFoundGetOrderRefund()
    {
        $this->mockHttpClient
            ->method('request')
            ->willThrowException(
                \CoinGate\Exception\Api\RefundNotFound::factory('Refund not found', 404)
            );

        $this->expectException(\CoinGate\Exception\Api\RefundNotFound::class);

        $this->service->get($this->orderId, 0);
    }

    public function testGetOrderRefundsOrRefunds()
    {
        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'current_page' => 1,
                    'per_page' => 20,
                    'total_refunds' => 1,
                    'total_pages' => 1,
                    'refunds' => [
                        [ 'id' => 1 ]
                    ]
                ]),
                200
            ]);

        $response = $this->service->list($this->orderId);

        $this->assertNotEmpty($response);

        $response = $this->service->list();

        $this->assertNotEmpty($response);
    }

    public function testNotFoundGetOrderRefunds()
    {
        $this->mockHttpClient
            ->method('request')
            ->willThrowException(
                \CoinGate\Exception\Api\RefundNotFound::factory('Refund not found', 404)
            );

        $this->expectException(\CoinGate\Exception\Api\RefundNotFound::class);

        $this->service->list(0);
    }
}
