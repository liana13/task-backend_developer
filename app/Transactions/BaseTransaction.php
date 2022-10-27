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
    public const USER__TYPE_BUSINESS = 'business';

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

    /**
     * set data
     * @param array $data <mixed>
     *
     * @return static
     */
    public function setData(array $data): BaseTransaction
    {
        $this->amount = $data['operation_amount'] ?? 0;
        $this->currency = $data['operation_currency'] ?? self::DEFAULT_CURRENCY;
        $this->userId = $data['user_id'] ?? null;
        $this->userType = $data['user_type'] ?? null;
        $this->date = $data['date'] ?? now();

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
     * rule for operation type
     * @param float $amount
     * @param float $percent
     *
     * @return float
     */
    abstract protected function rule(float $amount, float $percent): float;

    /**
     * get calculated commission
     * @return float
     */
    public function getCommission(): float
    {
        return $this->calculateCommission();
    }
}
