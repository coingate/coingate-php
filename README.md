# CoinGate PHP library for API v2

The CoinGate PHP library provides convenient access to the CoinGate API from applications written in the PHP language.

## Requirements

PHP 7.3.0 and later.

## Composer

You can install library via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require coingate/coingate-php
```

## Manual Installation

If you do not wish to use Composer, you can download the [latest release](https://github.com/coingate/coingate-php/releases). Then, to use the library, include the `init.php` file.

```php
require_once('/path/to/coingate-php/init.php');
```

## Dependencies

The library require the following extensions in order to work properly:

-   [`curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer
-   [`json`](https://secure.php.net/manual/en/book.json.php)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Getting Started

You can sign up for a CoinGate account at <https://coingate.com> for production and <https://sandbox.coingate.com> for testing (sandbox).

Please note, that for Sandbox you must generate separate API credentials on <https://sandbox.coingate.com>. API credentials generated on <https://coingate.com> will not work for Sandbox mode.

Usage of CoinGate PHP library looks like:

```php
$client = new \CoinGate\Client('YOUR_API_TOKEN');
```

In order, to use sandbox mode, you need to set second parameter to `true`.

```php
$client = new \CoinGate\Client('YOUR_API_TOKEN', true);
```

If you plan to use Public API endpoints only, authentication is not required.

```php
$client = new CoinGate\Client();

// if needed you can set configuration parameters later
$client->setApiKey('YOUR_API_TOKEN');
$client->setEnvironment('sandbox');
```

## Payment Gateway API

### Create Order

Create order at CoinGate and redirect shopper to invoice (payment_url).

```php
$params = [
    'order_id'          => 'YOUR-CUSTOM-ORDER-ID-115',
    'price_amount'      => 1050.99,
    'price_currency'    => 'USD',
    'receive_currency'  => 'EUR',
    'callback_url'      => 'https://example.com/payments?token=6tCENGUYI62ojkuzDPX7Jg',
    'cancel_url'        => 'https://example.com/cart',
    'success_url'       => 'https://example.com/account/orders',
    'title'             => 'Order #112',
    'description'       => 'Apple Iphone 13'
];

try {
    $order = $client->order->create($params);
} catch (\CoinGate\Exception\ApiErrorException $e) {
    // something went wrong...
}

echo $order->id;
```

### Checkout

Placing created order with pre-selected payment currency (BTC, LTC, ETH, etc). Display payment_address and pay_amount for shopper or redirect to payment_url. Can be used to white-label invoices.

```php
$checkout = $client->order->checkout(7294, [
    'pay_currency' => 'BTC'
]);
```

### Get Order

After creating an order, you will get an ORDER ID. This ID will be used for GET ORDER requests.

```php
$order = $client->order->get(7294);
```

### List Orders

Retrieving information of all placed orders.

```php
$orders = $client->order->list([
    'created_at' => [
        'from' => '2022-01-25'
    ]
]);
```

## Public API

### Get Exchange Rate

Current exchange rate for any two currencies, fiat or crypto. This endpoint is public, authentication is not required.

```php
$client->getExchangeRate('BTC', 'EUR');
```

### List Exchange Rates

Current CoinGate exchange rates for Merchants and Traders. This endpoint is public, authentication is not required.

```php
$client->listExchangeRates();
```

### Ping

A health check endpoint for CoinGate API. This endpoint is public, authentication is not required.

```php
$client->ping();
```

### IP Addresses

Get IP addresses of CoinGate servers

```php
$client->getIPAddresses();
```

### Currencies

```php
$client->getCurrencies();

// Crypto + Native + Merchant Pay 
$client->getCheckoutCurrencies();

// get Merchant Pay currencies only
$client->getMerchantPayCurrencies();

// get Merchant Receive currencies only
$client->getMerchantPayoutCurrencies();
```

### Platforms

```php
$client->getPlatforms();
```

## Custom Request Timeout

To modify request timeouts (connect or total, in seconds) you'll need to tell the API client to use a CurlClient other than its default. You'll set the timeouts in that CurlClient.

```php
// set up your tweaked Curl client
$curl = new \CoinGate\HttpClient\CurlClient();
$curl->setTimeout(10);
$curl->setConnectTimeout(5);

// tell CoinGate Library to use the tweaked Curl client
\CoinGate\Client::setHttpClient($curl);

// use the CoinGate API client as you normally would
```

## Test API Connection

```php
$result = \CoinGate\Client::testConnection('YOUR_API_TOKEN');
```

In order, to test API connection on sandbox mode, you need to set second parameter to `true`.

```php
$result = \CoinGate\Client::testConnection('YOUR_API_TOKEN', true);
```

## Attention plugin developers

Are you writing a plugin that integrates CoinGate and embeds our library? Then please use the setAppInfo function to identify your plugin. For example:

```php
\CoinGate\Client::setAppInfo("MyAwesomePlugin", "1.0.0");
```

The method should be called once, before any request is sent to the API. The second parameter is optional.