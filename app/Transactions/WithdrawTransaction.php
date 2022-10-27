<?php

namespace App\Transactions;

class WithdrawTransaction extends BaseTransaction implements WithdrawTransactionInterface
{
    /**
     * @param float $withdraw
     * @param float $percent
     * @return float
     */
     private function chargedOfWithdrawBusinessAmount(float $withdraw, float $percent = 0.5): float
     {
         return $withdraw * $percent / 100;
     }

     private function chargedOfWithdrawPrivateAmount(float $withdraw, float $percent = 0.3, float $freeLimit = 1000): float
     {
         $exchangedWithdraw = $this->exchangeWithdrawAmount($withdraw, $this->currency);
         if (count($this->weeklyWithdraws) == 0 && $exchangedWithdraw >= $freeLimit) {
             $withdrawRemain = $this->exchangeBackWithdrawAmount($exchangedWithdraw - $freeLimit, $this->currency);
             return $withdrawRemain * $percent / 100;
         }

         return $withdraw * $percent / 100;
     }

     private function exchangeWithdrawAmount(float $withdraw, string $currency): float
     {
         $rates = $this->exchangeRates();
         return isset($rates[$currency]) ? $withdraw * $rates[$currency] : $withdraw;
     }

     private function exchangeBackWithdrawAmount(float $withdraw, string $currency): float
     {
         $rates = $this->exchangeRates();
         return isset($rates[$currency]) ? $withdraw / $rates[$currency] : $withdraw;
     }

     protected function calculateCommission(): float
     {
         if ($this->userType == self::USER_TYPE_BUSINESS) {
             return $this->roundUp($this->chargedOfWithdrawBusinessAmount($this->amount));
         }
         return $this->roundUp($this->chargedOfWithdrawPrivateAmount($this->amount));
     }
}
