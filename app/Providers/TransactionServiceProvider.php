<?php

namespace App\Providers;

use App\Transactions\DepositTransaction;
use App\Transactions\DepositTransactionInterface;
use App\Transactions\WithdrawTransaction;
use App\Transactions\WithdrawTransactionInterface;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
{
    public array $bindings = [
        DepositTransactionInterface::class => DepositTransaction::class,
        WithdrawTransactionInterface::class => WithdrawTransaction::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        foreach ($this->bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
