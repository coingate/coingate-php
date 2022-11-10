<?php

namespace CoinGate\Services;

class WithdrawalService extends AbstractService
{
    /**
     * Retrieving information of a specific withdrawal.
     *
     * @param  int $id
     * @return mixed
     */
    public function get(int $id)
    {
        return $this->request('get', $this->buildPath('/v2/withdrawals/%s', $id));
    }

    /**
     * Retrieves all withdrawals
     *
     * @return mixed
     */
    public function list()
    {
        return $this->request('get', '/v2/withdrawals');
    }
}
