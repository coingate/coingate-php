<?php

namespace CoinGate\Exception;

/**
 * RateLimitException is thrown when API request limit is exceeded and HTTP Status: 429 (Too Many Requests).
 */
class RateLimitException extends ApiErrorException
{
}
