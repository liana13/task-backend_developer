<?php

namespace App\Transactions;

class DepositTransaction extends BaseTransaction implements DepositTransactionInterface
{
    /**
     * @param float $deposit
     * @param float $percent
     * @return float
     */
    private function chargedOfDepositAmount(float $deposit, float $percent = 0.03): float
    {
        return $deposit / $percent * 100;
    }

    protected function calculateCommission(): float
    {
        return ceil($this->rule($this->amount));
        // TODO: Implement calculateCommission() method.
    }

    protected function rule(float $amount, float $percent = 0.03): float
    {
        return $amount - $this->chargedOfDepositAmount($amount, $percent);
    }
}
