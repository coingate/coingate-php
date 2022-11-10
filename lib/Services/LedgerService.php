<?php

namespace CoinGate\Services;

class LedgerService extends AbstractService
{
    /**
     * Retrieves a specific ledger account.
     *
     * @param  string $id
     * @return mixed
     */
    public function get(string $id)
    {
        return $this->request('get', $this->buildPath('/v2/ledger/accounts/%s', $id));
    }

    /**
     * Retrieves all ledger accounts.
     *
     * @param  string[] $params
     * @return mixed
     */
    public function list(array $params = [])
    {
        return $this->request('get', '/v2/ledger/accounts', $params);
    }
}
