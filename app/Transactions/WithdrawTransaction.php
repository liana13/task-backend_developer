<?php

namespace App\Transactions;

class WithdrawTransaction extends BaseTransaction implements WithdrawTransactionInterface
{

    protected function calculateCommission(): float
    {
        // TODO: Implement calculateCommission() method.
    }

    protected function rule(float $amount, float $percent): float
    {
        // TODO: Implement rule() method.
    }
}
