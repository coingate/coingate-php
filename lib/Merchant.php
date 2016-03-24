<?php
namespace CoinGate;

class Merchant extends CoinGate
{
  private $app_id             = '';
  private $api_key            = '';
  private $api_secret         = '';
  private $mode               = 'sandbox'; // live or sandbox
  private $version            = 'v1';
  private $api_url            = '';
  private $user_agent         = '';

  public function __construct($config = array())
  {
    if (isset($config)) {
      $this->initialize($config);
    }
  }

  public function initialize($config)
  {
    foreach($config as $key => $value) {
      if (in_array($key, array('app_id', 'api_key', 'api_secret', 'mode', 'user_agent')))
      {
          $this->$key = trim($value);
      }

      if (empty($this->user_agent)) {
        $this->user_agent = parent::USER_AGENT_ORIGIN . ' v' . parent::VERSION;
      }
    }

    $this->setApiUrl();
  }

  public function createOrder($params = array())
  {
    $this->request('/orders', 'POST', $params);
  }

  public function getOrder($order_id)
  {
    $this->request('/orders/' . $order_id);
  }

  public function testConnection()
  {
    $this->request('/auth/test');

    return $this->success && $this->response == 'OK';
  }

  private function request($url, $method = 'GET', $params = array())
  {
      $url        = $this->api_url . $url;
      $nonce      = $this->nonce();
      $message    = $nonce . $this->app_id . $this->api_key;
      $signature  = hash_hmac('sha256', $message, $this->api_secret);
      $headers    = array();
      $headers[]  = 'Access-Key: ' . $this->api_key;
      $headers[]  = 'Access-Nonce: ' . $nonce;
      $headers[]  = 'Access-Signature: ' . $signature;
      $curl = curl_init();

      $curl_options = array(
        CURLOPT_RETURNTRANSFER  => 1,
        CURLOPT_URL             => $url
      );

      if ($method == 'POST') {
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        array_merge($curl_options, array(CURLOPT_POST => 1));
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
      }

      curl_setopt_array($curl, $curl_options);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agent);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

      $response       = curl_exec($curl);
      $http_status    = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      $this->curl_error['message']    = curl_error($curl);
      $this->curl_error['number']     = curl_errno($curl);

      curl_close($curl);

      $this->success      = $http_status == 200;
      $this->status_code  = $http_status;
      $this->response     = $this->success ? json_decode($response, TRUE) : $response;
  }

  private function nonce()
  {
    return time();
  }

  private function setApiUrl()
  {
    $this->api_url = strtolower($this->mode) == 'live' ? 'https://coingate.com/api/' : 'https://sandbox.coingate.com/api/';
    $this->api_url .= $this->version;
  }
}
