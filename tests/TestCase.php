<?php

namespace CoinGate;

class TestCase extends \PHPUnit_Framework_TestCase
{
    const APP_ID      = 1;
    const API_KEY     = 'iob0ctFgeHLajvfxzYnIPB';
    const API_SECRET  = 'ytaqXRWZ17ONlpshPTQuF50rIVLwBbmi';
    const ENVIRONMENT = 'sandbox';

    public static function getGoodAuthentication()
    {
        return array(
            'app_id'      => self::APP_ID,
            'api_key'     => self::API_KEY,
            'api_secret'  => self::API_SECRET,
            'environment' => self::ENVIRONMENT,
        );
    }
}