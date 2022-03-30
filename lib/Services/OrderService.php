<?php

namespace CoinGate\Services;

use CoinGate\Resources\CreateOrder;
use CoinGate\Resources\Checkout;
use CoinGate\Resources\Order;

class OrderService extends AbstractService
{
    /**
     * Create order at CoinGate and redirect shopper to invoice (payment_url).
     *
     * @param  string[] $params
     * @return CreateOrder|mixed
     */
    public function create(array $params = [])
    {
        return $this->request('post', '/v2/orders', $params);
    }

    /**
     * @param  int   $id
     * @param  string[] $params
     * @return Checkout|mixed
     */
    public function checkout(int $id, array $params = [])
    {
        return $this->request('post', $this->buildPath('/v2/orders/%s/checkout', $id), $params);
    }

    /**
     * Retrieving information of a specific order by CoinGate order ID.
     *
     * @param  int $id
     * @return Order|mixed
     */
    public function get(int $id)
    {
        return $this->request('get', $this->buildPath('/v2/orders/%s', $id));
    }

    /**
     * Retrieving information of all placed orders.
     *
     * @param  string[] $params
     * @return Order[]|mixed
     */
    public function list(array $params = [])
    {
        return $this->request('get', '/v2/orders', $params);
    }
}
