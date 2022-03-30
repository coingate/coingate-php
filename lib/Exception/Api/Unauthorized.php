<?php

namespace CoinGate\Exception\Api;

use CoinGate\Exception\ApiErrorException;

/**
 * Unauthorized is thrown when HTTP Status: 401 (Unauthorized).
 */
class Unauthorized extends ApiErrorException
{
}
