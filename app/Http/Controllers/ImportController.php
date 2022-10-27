<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Imports\TransactionsImport;
use App\Transactions\BaseTransaction;
use App\Transactions\PassToHandle;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Excel;

class ImportController extends Controller
{
    use PassToHandle;

    /**
     * @param ImportRequest $request
     * @param TransactionsImport $import
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function import(ImportRequest $request, TransactionsImport $import): RedirectResponse
    {
        $commissions = [];
        $data = $import->toArray($request->validated()['import'], env('FILESYSTEM_DRIVER', 'local'), Excel::CSV);
        foreach ($data[0] as $key => $record) {
            $transactions = $data[0];
            unset($transactions[$key]);
            $c = $this->passToHandle($record['operation_type'], $record, $transactions);
            $commissions []= number_format($c,2) . ' ' . $record['operation_currency'];
        }
        return redirect()->route('dashboard')->with('commissions', $commissions);
    }
}
