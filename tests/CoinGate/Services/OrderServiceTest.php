<?php

namespace CoinGate\Services;

use CoinGate\TestCase;

class OrderServiceTest extends TestCase
{
    /** @var OrderService */
    private $service;

    /**
     * @before
     */
    protected function setUpService()
    {
        $client = $this->createSandboxClient();

        $this->service = new OrderService($client);
    }

    public function testCreateOrder()
    {
        $params = self::getGoodPostParams();

        $order = $this->service->create($params);

        $this->assertObjectHasAttribute('id', $order);
        $this->assertSame($order->order_id, $params['order_id']);

        return $order;
    }

    public function testInvalidCreateOrder()
    {
        $this->expectException('CoinGate\Exception\Api\OrderIsNotValid');

        $this->service->create([]);
    }

    /**
     * @depends testCreateOrder
     */
    public function testCheckout($order)
    {
        $response = $this->service->checkout($order->id, [
            'pay_currency' => 'BTC'
        ]);

        $this->assertSame($order->id, $response->id);
    }

    public function testInvalidCheckout()
    {
        $this->expectException('CoinGate\Exception\Api\OrderNotFound');

        $this->service->checkout(0, [
            'pay_currency' => 'BTC'
        ]);
    }

    /**
     * @depends testCreateOrder
     */
    public function testGetOrder($order)
    {
        $response = $this->service->get($order->id);

        $this->assertSame($order->id, $response->id);
    }

    public function testInvalidGetOrder()
    {
        $this->expectException('CoinGate\Exception\Api\OrderNotFound');

        $this->service->get(0);
    }

    public function testListOrders()
    {
        $response = $this->service->list([
            'created_at' => [
                'from' => '2022-01-25'
            ]
        ]);

        $this->assertNotEmpty($response);
    }

    public static function getGoodPostParams(): array
    {
        return [
            'order_id'          => 'YOUR-CUSTOM-ORDER-ID-115',
            'price_amount'      => 1050.99,
            'price_currency'    => 'USD',
            'receive_currency'  => 'EUR',
            'callback_url'      => 'https://example.com/payments?token=6tCENGUYI62ojkuzDPX7Jg',
            'cancel_url'        => 'https://example.com/cart',
            'success_url'       => 'https://example.com/account/orders',
            'title'             => 'Order #112',
            'description'       => 'Apple Iphone 6'
        ];
    }
}
