<?php

namespace CoinGate\HttpClient;

interface ClientInterface
{
    public function request(string $method, string $absUrl, array $headers = [], array $params = []);
}
