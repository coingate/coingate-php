<?php

namespace CoinGate;

class CoinGate
{
    const VERSION = '3.0.4';
    const USER_AGENT_ORIGIN = 'CoinGate PHP Library';

    public static $auth_token = '';
    public static $environment = 'live';
    public static $user_agent = '';
    public static $curlopt_ssl_verifypeer = false;

    public static function config($authentication)
    {
        if (isset($authentication['auth_token'])) {
            self::$auth_token = $authentication['auth_token'];
        }

        if (isset($authentication['environment'])) {
            self::$environment = $authentication['environment'];
        }

        if (isset($authentication['user_agent'])) {
            self::$user_agent = $authentication['user_agent'];
        }

        if (isset($authentication['curlopt_ssl_verifypeer'])) {
            self::$curlopt_ssl_verifypeer = $authentication['curlopt_ssl_verifypeer'];
        }
    }

    public static function testConnection($authentication = array())
    {
        try {
            self::request('/auth/test', 'GET', array(), $authentication);

            return true;
        } catch (\Exception $e) {
            return get_class($e) . ': ' . $e->getMessage();
        }
    }

    public static function request($url, $method = 'POST', $params = array(), $authentication = array())
    {
        $auth_token = isset($authentication['auth_token']) ? $authentication['auth_token'] : self::$auth_token;
        $environment = isset($authentication['environment']) ? $authentication['environment'] : self::$environment;
        $user_agent = isset($authentication['user_agent']) ? $authentication['user_agent'] : (isset(self::$user_agent)
            ? self::$user_agent : (self::USER_AGENT_ORIGIN . ' v' . self::VERSION));
        $curlopt_ssl_verifypeer = isset($authentication['curlopt_ssl_verifypeer'])
            ? $authentication['curlopt_ssl_verifypeer'] : self::$curlopt_ssl_verifypeer;

        // Check if credentials was passed
        if (empty($auth_token)) {
            \CoinGate\Exception::throwException(400, array('reason' => 'AuthTokenMissing'));
        }

        // Check if right environment passed
        $environments = array('live', 'sandbox');

        if (!in_array($environment, $environments)) {
            $availableEnvironments = join(', ', $environments);
            \CoinGate\Exception::throwException(400, array(
                'reason' => 'BadEnvironment',
                'message' => "Environment does not exist. Available environments: $availableEnvironments"
            ));
        }

        $url = ($environment === 'sandbox' ? 'https://api-sandbox.coingate.com/v2' : 'https://api.coingate.com/v2')
            . $url;
        $headers = array();
        $headers[] = 'Authorization: Token ' . $auth_token;
        $curl = curl_init();

        $curl_options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        );

        if ($method == 'POST') {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            array_merge($curl_options, array(CURLOPT_POST => 1));
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt_array($curl, $curl_options);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $curlopt_ssl_verifypeer);


        $raw_response = curl_exec($curl);
        $decoded_response = json_decode($raw_response, true);
        $response = $decoded_response ? $decoded_response : $raw_response;
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($http_status === 200) {
            return $response;
        } else {
            \CoinGate\Exception::throwException($http_status, $response);
        }
    }
}
