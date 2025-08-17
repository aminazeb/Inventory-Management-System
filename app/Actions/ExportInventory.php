<?php

namespace App\Actions;

use Illuminate\Http\Request;
use App\Exports\InventoryExport;
use Lorisleiva\Actions\Concerns\AsController;
use Maatwebsite\Excel\Facades\Excel;

class ExportInventory
{
    use AsController;

    public function asController(Request $request)
    {
        $filename = 'inventory_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new InventoryExport($request), $filename);
    }
}
