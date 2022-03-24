<?php

namespace CoinGate;

interface ClientInterface
{
    /**
     * @param string $method
     * @param string $path
     * @param array<string, string> $params
     * @return mixed
     */
    public function request(string $method, string $path, array $params = []);
}
