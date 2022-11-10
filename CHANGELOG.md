CoinGate PHP library release notes
============================

v4.2.0
---
* Added support for Refund API calls.
  - Added support for [Create Order Refund](https://developer.coingate.com/reference/create-refund)
  - Added support for [Get Order Refund](https://developer.coingate.com/reference/get-order-refund)
  - Added support for [Get Order Refunds](https://developer.coingate.com/reference/get-refund)
  - Added support for [Get Refunds](https://developer.coingate.com/reference/get-refunds)
* Added support for Ledger API calls.
  - Added support for [Get Account](https://developer.coingate.com/reference/get-account)
  - Added support for [List Accounts](https://developer.coingate.com/reference/accounts)
* Added support for Withdrawal API calls.
  - Added support for [Get Withdrawals](https://developer.coingate.com/reference/get-withdrawals)
  - Added support for [Get Withdrawal](https://developer.coingate.com/reference/get-withdrawal)

v4.1.0
---
* ApiKey is no more mandatory when creating a Client. Useful when you want to perform Public API calls only.
* Added possibility for late ``$client->setApiKey('YOUR_API_TOKEN')`` and ``$client->setEnvironment('sandbox')`` assignments.
* Added support for Public API calls.
  - Added support for [Get Exchange Rate](https://developer.coingate.com/docs/get-rate)
  - Added support for [List Exchange Rates](https://developer.coingate.com/docs/list-rates)
  - Added support for [Ping](https://developer.coingate.com/docs/ping)
  - Added support for [IP Addresses](https://developer.coingate.com/docs/ip-addresses)
  - Added support for [Currencies](https://developer.coingate.com/docs/currencies)
  - Added support for [Platforms](https://developer.coingate.com/docs/platforms)

v4.0.0
----
* Library now requires PHP 7.3.0 or greater.
* Added support for an additional Payment Gateway API calls.
  - Added support for [Checkout](https://developer.coingate.com/docs/checkout)
  - Added support for [List Orders](https://developer.coingate.com/docs/list-orders)
* Added possibility to extend and/or use custom HttpClient.

> ### Breaking Changes
* Library was completely refactored from the ground up and resulted into usage changes. 
  - ```\CoinGate\CoinGate::config(...)``` => ```$client = new \CoinGate\Client('YOUR_API_TOKEN')```
  - ```\CoinGate\Merchant\Order::create($params)``` => ```$client->order->create($params)```
  - ```\CoinGate\Merchant\Order::find(7294)``` => ```$client->order->get(7294)```