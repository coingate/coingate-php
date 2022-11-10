<?php

namespace CoinGate\Exception\Api;

use CoinGate\Exception\ApiErrorException;

/**
 * LedgerAccountNotFound is thrown when ledger account does not exist and HTTP Status: 404 (Not Found).
 */
class LedgerAccountNotFound extends ApiErrorException
{
}
