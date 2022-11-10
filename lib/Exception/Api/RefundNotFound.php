<?php

namespace CoinGate\Exception\Api;

use CoinGate\Exception\ApiErrorException;

/**
 * RefundNotFound is thrown when refund does not exist and HTTP Status: 404 (Not Found).
 */
class RefundNotFound extends ApiErrorException
{
}
