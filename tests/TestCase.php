<?php

namespace CoinGate;

class TestCase extends \PHPUnit_Framework_TestCase
{
    const AUTH_TOKEN  = '-39RHqyAiyBmpwAEz9FcFxcVZDqbGmvKXTdHztny';
    const ENVIRONMENT = 'sandbox';

    public static function getGoodAuthentication()
    {
        return array(
            'auth_token'  => self::AUTH_TOKEN,
            'environment' => self::ENVIRONMENT,
        );
    }
}