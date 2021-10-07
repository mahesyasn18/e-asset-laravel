<?php

namespace App\Exports;

use App\Models\RequestModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class RequestExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('admin.exports.excel', [
            'requests' => RequestModel::with("admin", "user")->where("status_id", 4)->get()
        ]);
    }
}
