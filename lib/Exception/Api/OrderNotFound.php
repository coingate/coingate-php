<?php

namespace CoinGate\Exception\Api;

use CoinGate\Exception\ApiErrorException;

/**
 * OrderNotFound is thrown when order does not exist and HTTP Status: 422 (Unprocessable Entity) or 404 (Not Found).
 */
class OrderNotFound extends ApiErrorException
{
}
