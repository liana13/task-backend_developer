<?php

namespace App\Transactions;

use Illuminate\Contracts\Container\BindingResolutionException;

abstract class BaseTransaction
{
    /** @const string DEFAULT_CURRENCY */
    public const DEFAULT_CURRENCY = 'USD';

    /** @const string OPERATION_TYPE_WITHDRAW */
    public const OPERATION_TYPE_WITHDRAW = 'withdraw';

    /** @const string OPERATION_TYPE_DEPOSIT */
    public const OPERATION_TYPE_DEPOSIT = 'deposit';

    /** @const string USER_PRIVATE */
    public const USER_TYPE_PRIVATE = 'private';

    /** @const string USER__TYPE_BUSINESS */
    public const USER_TYPE_BUSINESS = 'business';

    /** @var string $currency */
    protected string $currency;

    /** @var float $amount */
    protected float $amount;

    /** @var int $userId */
    protected int $userId;

    /** @var string $userType */
    protected string $userType;

    /** @var string $date */
    protected string $date;

    /** @var array $weeklyWithdraws */
    protected array $weeklyWithdraws;

    /**
     * round number to up
     */
    public function roundUp($value, $precision = 2) : float
    {
        $pow = pow (10, $precision);
        return ( ceil($pow * $value) + ceil ($pow * $value - ceil ($pow * $value)) ) / $pow;
    }

    /**
     * get exchange rates
     */
    public function exchangeRates()
    {
        $exchange = json_decode(file_get_contents(env('EXCHANGE_URL')), true);
        return $exchange['rates'];
    }

    /**
     * check weekly transactions
     */
    public function isInWeek($tdate)
    {
        $transactionDate = \Carbon\Carbon::parse($this->date);
        $wDate = \Carbon\Carbon::parse($tdate);

        return $transactionDate->gte($wDate) && $wDate->weekOfYear == $transactionDate->weekOfYear && $wDate->diff($transactionDate)->days <= 7;
    }

    /**
     * set data
     * @param array $data <mixed>
     *
     * @return static
     */
    public function setData(array $data, array $transactions): BaseTransaction
    {
        $this->amount = $data['operation_amount'] ?? 0;
        $this->currency = $data['operation_currency'] ?? self::DEFAULT_CURRENCY;
        $this->userId = $data['user_id'] ?? null;
        $this->userType = $data['user_type'] ?? null;
        $this->date = $data['date'] ?? now();
        $this->weeklyWithdraws = [];

        $count = 0;
        foreach ($transactions as $t) {
            if ($t['user_id'] == $this->userId && $t['user_type'] == self::USER_TYPE_PRIVATE && $t['operation_type'] == self::OPERATION_TYPE_WITHDRAW
                && $this->isInWeek($t['date']) && $count < 3
            ) {
                $this->weeklyWithdraws []= $t;
                $count ++;
            }
        }

        return $this;
    }

    /**
     * get particular operation instance of implementation
     * @param string $operationType
     * @return DepositTransactionInterface|WithdrawTransactionInterface|null
     * @throws BindingResolutionException
     */
    public static function getInstance(string $operationType)
    {
        if ($operationType === self::OPERATION_TYPE_DEPOSIT) {
            return app()->make(DepositTransactionInterface::class);
        }

        if ($operationType === self::OPERATION_TYPE_WITHDRAW) {
            return app()->make(WithdrawTransactionInterface::class);
        }
        return null;
    }

    /**
     * calculates a commission fee based on defined rules
     */
    abstract protected function calculateCommission(): float;

    /**
     * get calculated commission
     * @return float
     */
    public function getCommission(): float
    {
        return $this->calculateCommission();
    }
}
