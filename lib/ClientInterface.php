<?php

namespace CoinGate;

interface ClientInterface
{
    public function request(string $method, string $path, array $params = []);
}
