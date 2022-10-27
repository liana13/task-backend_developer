<?php

namespace App\Imports;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransactionsImport implements ToArray,
    WithChunkReading,
    WithHeadingRow
{
    use Importable;

    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * @param array $array
     * @return array
     */
    public function array(array $array): array
    {

        return [
            'operation_amount' => $row['operation_amount'] ?? null,
            'operation_currency' => $row['operation_currency'] ?? null,
            'user_id' => $row['user_id'] ?? null,
            'user_type' => $row['user_type'] ?? null,
            'date' => $row['date'] ?? null,
        ];
    }

}
