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
        return $deposit * $percent / 100;
    }

    protected function calculateCommission(): float
    {
        return $this->roundUp($this->chargedOfDepositAmount($this->amount));
    }
}
