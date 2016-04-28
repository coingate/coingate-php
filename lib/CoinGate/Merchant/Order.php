<?php
namespace CoinGate\Merchant;

use CoinGate\Merchant;
use CoinGate\OrderIsNotValid;
use CoinGate\OrderNotFound;

class Order extends Merchant
{
    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function toHash()
    {
        return $this->order;
    }

    public function __get($name)
    {
        return $this->order[$name];
    }

    public static function find($orderId, $authentication = array())
    {
        try {
            return self::findOrFail($orderId, $authentication);
        } catch (OrderNotFound $e) {
            return false;
        }
    }

    public static function findOrFail($orderId, $authentication = array())
    {
        $order = \CoinGate::request('/orders/' . $orderId, 'GET', array(), $authentication);

        return new self($order);
    }

    public static function create($params, $authentication = array())
    {
        try {
            return self::createOrFail($params, $authentication);
        } catch (OrderIsNotValid $e) {
            return false;
        }
    }

    public static function createOrFail($params, $authentication = array())
    {
        $order = \CoinGate::request('/orders', 'POST', $params, $authentication);

        return new self($order);
    }
}
