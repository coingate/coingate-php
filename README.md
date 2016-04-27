# CoinGate PHP library

PHP library for CoinGate API.

You can sign up for a CoinGate account at <https://coingate.com> for production and <https://sandbox.coingate.com> for testing (sandbox).

## Composer

You can install library via [Composer](http://getcomposer.org/). Run the following command in your terminal:

```bash
composer require coingate/coingate-php
```

To use library, use Composer's autoload:

```php
require_once('vendor/autoload.php');
```

## Manual Installation

```php
require_once('/path/to/coingate-php/init.php');
```

## Getting Started

Usage of CoinGate PHP library.

### Creating Merchant Order

https://developer.coingate.com/docs/create-order

```php
$coingate = new \CoinGate\Merchant(array('app_id' => 'YOUR_APP_ID', 'api_key' => 'YOUR_API_KEY', 'api_secret' => 'YOUR_API_SECRET'));

$coingate->createOrder(array(
                           'order_id'          => 'YOUR-CUSTOM-ORDER-ID-115',
                           'price'             => 1050.99,
                           'currency'          => 'USD',
                           'receive_currency'  => 'EUR',
                           'callback_url'      => 'https://example.com/payments/callback?token=6tCENGUYI62ojkuzDPX7Jg',
                           'cancel_url'        => 'https://example.com/cart',
                           'success_url'       => 'https://example.com/account/orders',
                           'title'             => 'Order #112',
                           'description'       => 'Apple Iphone 6'
                       ));

echo 'Response HTTP Status: ' . $coingate->status_code . "\n";

if ($coingate->success) {
    echo 'SUCCESS' . "\n";
    var_dump(json_decode($coingate->response));
} else {
    echo 'FAIL' . "\n";
    echo $coingate->response;
}
```

### Getting Merchant Order

https://developer.coingate.com/docs/get-order

```php
$coingate = new \CoinGate\Merchant(array('app_id' => 'YOUR_APP_ID', 'api_key' => 'YOUR_API_KEY', 'api_secret' => 'YOUR_API_SECRET'));
$coingate->getOrder(1087999);

echo 'Response HTTP Status: ' . $coingate_service->status_code . "\n";

if ($coingate->success) {
    echo 'SUCCESS' . "\n";
    var_dump(json_decode($coingate->response));
} else {
    echo 'FAIL' . "\n";
    echo $coingate->response;
}
```

