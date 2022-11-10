<?php

namespace CoinGate\Services;

class RefundService extends AbstractService
{
    /**
     * Creates a refund for an order.
     *
     * @param  int $orderId
     * @param  string[] $params
     * @return mixed
     */
    public function create(int $orderId, array $params = [])
    {
        return $this->request('post', $this->buildPath('/v2/orders/%s/refunds', $orderId), $params);
    }

    /**
     * Retrieves a specific refund for an order.
     *
     * @param  int $orderId
     * @param  int $id
     * @return mixed
     */
    public function get(int $orderId, int $id)
    {
        return $this->request('get', $this->buildPath('/v2/orders/%s/refunds/%s', $orderId, $id));
    }

    /**
     * Retrieves all refunds (optionally could be filtered by an order).
     *
     * @param  int|null $orderId
     * @param  string[] $params
     * @return mixed
     */
    public function list(int $orderId = null, array $params = [])
    {
        if ($orderId === null) {
            return $this->request('get', '/v2/refunds', $params);
        }

        return $this->request('get', $this->buildPath('/v2/orders/%s/refunds', $orderId), $params);
    }
}
