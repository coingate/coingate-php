<?php

namespace CoinGate;

use CoinGate\APIError\CredentialsMissing;
use CoinGate\APIError\BadEnvironment;
use CoinGate\APIError\BadRequest;
use CoinGate\APIError\BadCredentials;
use CoinGate\APIError\BadAuthToken;
use CoinGate\APIError\AccountBlocked;
use CoinGate\APIError\IpAddressIsNotAllowed;
use CoinGate\APIError\Unauthorized;
use CoinGate\APIError\PageNotFound;
use CoinGate\APIError\RecordNotFound;
use CoinGate\APIError\OrderNotFound;
use CoinGate\APIError\NotFound;
use CoinGate\APIError\OrderIsNotValid;
use CoinGate\APIError\UnprocessableEntity;
use CoinGate\APIError\RateLimitException;
use CoinGate\APIError\InternalServerError;
use CoinGate\APIError\APIError;

class Exception
{
    public static function formatError($error)
    {
        if (is_array($error)) {
            $reason = '';
            $message = '';

            if (isset($error['reason'])) {
                $reason = $error['reason'];
            }

            if (isset($error['message'])) {
                $message = $error['message'];
            }

            return "{$reason} {$message}";
        } else {
            return $error;
        }
    }

    public static function throwException($http_status, $error)
    {
        $reason = is_array($error) && isset($error['reason']) ? $error['reason'] : '';

        switch ($http_status) {
            case 400:
                switch ($reason) {
                    case 'CredentialsMissing':
                        throw new CredentialsMissing(self::formatError($error));
                    case 'BadEnvironment':
                        throw new BadEnvironment(self::formatError($error));
                    default:
                        throw new BadRequest(self::formatError($error));
                }
            //no break
            case 401:
                switch ($reason) {
                    case 'BadCredentials':
                        throw new BadCredentials(self::formatError($error));
                    case 'BadAuthToken':
                        throw new BadAuthToken(self::formatError($error));
                    case 'AccountBlocked':
                        throw new AccountBlocked(self::formatError($error));
                    case 'IpAddressIsNotAllowed':
                        throw new IpAddressIsNotAllowed(self::formatError($error));
                    default:
                        throw new Unauthorized(self::formatError($error));
                }
            //no break
            case 404:
                switch ($reason) {
                    case 'PageNotFound':
                        throw new PageNotFound(self::formatError($error));
                    case 'RecordNotFound':
                        throw new RecordNotFound(self::formatError($error));
                    case 'OrderNotFound':
                        throw new OrderNotFound(self::formatError($error));
                    default:
                        throw new NotFound(self::formatError($error));
                }
            //no break
            case 422:
                switch ($reason) {
                    case 'OrderIsNotValid':
                        throw new OrderIsNotValid(self::formatError($error));
                    default:
                        throw new UnprocessableEntity(self::formatError($error));
                }
            //no break
            case 429:
                throw new RateLimitException(self::formatError($error));
            case 500:
                throw new InternalServerError(self::formatError($error));
            case 504:
                throw new InternalServerError(self::formatError($error));
            default:
                throw new APIError(self::formatError($error));
        }
    }
}

