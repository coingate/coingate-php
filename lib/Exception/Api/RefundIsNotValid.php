<?php

namespace CoinGate\Exception\Api;

use CoinGate\Exception\ApiErrorException;

/**
 * RefundIsNotValid is thrown when refund is not valid and HTTP Status: 422 (Unprocessable Entity).
 */
class RefundIsNotValid extends ApiErrorException
{
}
