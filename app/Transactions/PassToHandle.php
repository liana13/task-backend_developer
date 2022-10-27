<?php

namespace App\Transactions;

use Illuminate\Contracts\Container\BindingResolutionException;

trait PassToHandle
{
    /**
     * @param string $operationType
     * @param array $data
     * @return float
     * @throws BindingResolutionException
     */
    private function passToHandle(string $operationType, array $data): float
    {
        return BaseTransaction::getInstance($operationType)->setData($data)->getCommission();
    }
}
