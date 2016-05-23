<?php
namespace CoinGate;

class Exception
{
    public static function formatError($error)
    {
        $reason = '';
        $message = '';

        if (isset($error['reason']))
            $reason = $error['reason'];

        if (isset($error['message']))
            $message = $error['message'];

        return "{$reason} {$message}";
    }

    public static function throwException($http_status, $error)
    {
        $reason = $error['reason'];

        switch ($http_status) {
            case 400:
                switch ($reason) {
                    case 'CredentialsMissing': throw new CredentialsMissing(self::formatError($error));
                    case 'BadEnvironment': throw new BadEnvironment(self::formatError($error));
                    default: throw new BadRequest(self::formatError($error));
                }
            case 401:
                switch ($reason) {
                    case 'BadCredentials': throw new BadCredentials(self::formatError($error));
                    default: throw new Unauthorized(self::formatError($error));
                }
            case 404:
                switch ($reason) {
                    case 'PageNotFound': throw new PageNotFound(self::formatError($error));
                    case 'RecordNotFound': throw new RecordNotFound(self::formatError($error));
                    case 'OrderNotFound': throw new OrderNotFound(self::formatError($error));
                    default: throw new NotFound(self::formatError($error));
                }
            case 422:
                switch ($reason) {
                    case 'OrderIsNotValid': throw new OrderIsNotValid(self::formatError($error));
                    default: throw new UnprocessableEntity(self::formatError($error));
                }
            case 500:
                switch ($reason) {
                    default: throw new InternalServerError(self::formatError($error));
                }
            default: throw new APIError(self::formatError($error));
        }
    }
}

