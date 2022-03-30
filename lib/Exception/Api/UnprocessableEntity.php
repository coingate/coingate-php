<?php

namespace CoinGate\Exception\Api;

use CoinGate\Exception\ApiErrorException;

/**
 * UnprocessableEntity is thrown when HTTP Status: 422 (Unprocessable Entity).
 */
class UnprocessableEntity extends ApiErrorException
{
}
