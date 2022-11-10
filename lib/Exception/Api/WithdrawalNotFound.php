<?php

namespace CoinGate\Exception\Api;

use CoinGate\Exception\ApiErrorException;

/**
 * WithdrawalNotFound is thrown when withdrawal does not exist and HTTP Status: 404 (Not Found).
 */
class WithdrawalNotFound extends ApiErrorException
{
}
