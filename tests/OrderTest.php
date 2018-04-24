<?php
namespace CoinGate;

class OrderTest extends TestCase
{
    public function testFindOrderNotFound()
    {
        $this->assertFalse(Merchant\Order::find(0, array(), self::getGoodAuthentication()));

        try {
            $this->assertFalse(Merchant\Order::findOrFail(0, array(), self::getGoodAuthentication()));
        } catch (\Exception $e) {
            $this->assertRegExp('/OrderNotFound/', $e->getMessage());
        }
    }

    public function testFindOrderFound()
    {
        $order = Merchant\Order::create(self::getGoodPostParams(), array(), self::getGoodAuthentication());
        $this->assertNotFalse(Merchant\Order::find($order->id, array(), self::getGoodAuthentication()));
    }

    public function testCreateOrderIsNotValid()
    {
        $this->assertFalse(Merchant\Order::create(array(), array(), self::getGoodAuthentication()));
        try {
            $this->assertFalse(Merchant\Order::createOrFail(array(), array(), self::getGoodAuthentication()));
        } catch (\Exception $e) {
            $this->assertRegExp('/OrderIsNotValid/', $e->getMessage());
        }
    }

    public function testCreateOrderValid()
    {
        $this->assertNotFalse(Merchant\Order::create(self::getGoodPostParams(), array(), self::getGoodAuthentication()));
    }

    public static function getGoodPostParams() {
        return array(
            'order_id'          => 'YOUR-CUSTOM-ORDER-ID-115',
            'price_amount'      => 1000.99,
            'price_currency'    => 'USD',
            'receive_currency'  => 'EUR',
            'callback_url'      => 'https://example.com/payments/callback?token=6tCENGUYI62ojkuzDPX7Jg',
            'cancel_url'        => 'https://example.com/cart',
            'success_url'       => 'https://example.com/account/orders',
            'title'             => 'Order #112',
            'description'       => 'Apple Iphone 6'
        );
    }
}