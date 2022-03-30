<?php

namespace CoinGate\HttpClient;

interface ClientInterface
{
    /**
     * @param string $method
     * @param string $absUrl
     * @param string[] $headers
     * @param array<string, string> $params
     * @return mixed
     */
    public function request(string $method, string $absUrl, array $headers = [], array $params = []);
}
