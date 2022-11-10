<?php

namespace CoinGate\Services;

use CoinGate\TestCase;

class OrderServiceTest extends TestCase
{
    private $mockHttpClient;

    /** @var \CoinGate\Services\OrderService */
    private $service;

    /**
     * @before
     */
    protected function setUpService()
    {
        $this->mockHttpClient = $this->mockHttpClient();

        $client = $this->createSandboxClient($this->mockHttpClient);

        $this->service = new OrderService($client);
    }

    public function testCreateOrder()
    {
        $myCustomOrderId = 'YOUR-CUSTOM-ORDER-ID-115';

        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'id' => 1,
                    'order_id' => $myCustomOrderId
                ]),
                200
            ]);

        $order = $this->service->create([
            'order_id'          => $myCustomOrderId,
            'price_amount'      => 1050.99,
            'price_currency'    => 'USD',
            'receive_currency'  => 'EUR',
            'callback_url'      => 'https://example.com/payments?token=6tCENGUYI62ojkuzDPX7Jg',
            'cancel_url'        => 'https://example.com/cart',
            'success_url'       => 'https://example.com/account/orders',
            'title'             => 'Order #112',
            'description'       => 'Apple Iphone 6'
        ]);

        $this->assertObjectHasAttribute('id', $order);
        $this->assertSame($order->order_id, $myCustomOrderId);

        return $order;
    }

    public function testInvalidCreateOrder()
    {
        $this->mockHttpClient
            ->method('request')
            ->willThrowException(
                \CoinGate\Exception\Api\OrderIsNotValid::factory('Order is not valid', 422)
            );

        $this->expectException(\CoinGate\Exception\Api\OrderIsNotValid::class);

        $this->service->create([]);
    }

    /**
     * @depends testCreateOrder
     */
    public function testCheckout($order)
    {
        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'id' => $order->id
                ]),
                200
            ]);

        $response = $this->service->checkout($order->id, [
            'pay_currency' => 'BTC'
        ]);

        $this->assertSame($order->id, $response->id);
    }

    public function testNotFoundCheckout()
    {
        $this->mockHttpClient
            ->method('request')
            ->willThrowException(
                \CoinGate\Exception\Api\OrderNotFound::factory('Order not found', 404)
            );

        $this->expectException(\CoinGate\Exception\Api\OrderNotFound::class);

        $this->service->checkout(0, [
            'pay_currency' => 'BTC'
        ]);
    }

    /**
     * @depends testCreateOrder
     */
    public function testGetOrder($order)
    {
        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'id' => $order->id
                ]),
                200
            ]);

        $response = $this->service->get($order->id);

        $this->assertSame($order->id, $response->id);
    }

    public function testNotFoundGetOrder()
    {
        $this->mockHttpClient
            ->method('request')
            ->willThrowException(
                \CoinGate\Exception\Api\OrderNotFound::factory('Order not found', 404)
            );

        $this->expectException(\CoinGate\Exception\Api\OrderNotFound::class);

        $this->service->get(0);
    }

    public function testListOrders()
    {
        $this->mockHttpClient
            ->method('request')
            ->willReturn([
                json_encode([
                    'current_page' => 1,
                    'per_page' => 20,
                    'total_orders' => 1,
                    'total_pages' => 1,
                    'orders' => [
                        [ 'id' => 1 ]
                    ]
                ]),
                200
            ]);

        $response = $this->service->list([
            'created_at' => [
                'from' => '2022-01-25'
            ]
        ]);

        $this->assertNotEmpty($response);
    }
}
