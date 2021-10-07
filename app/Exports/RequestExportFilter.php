<?php

namespace App\Exports;

use App\Models\RequestModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RequestExportFilter implements FromView
{
    private $first_date , $second_date;

    public function __construct($date1,$date2)
    {
        $this->first_date = date("Y-m-d",strtotime($date1));
        $this->second_date = date("Y-m-d",strtotime($date2));
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('admin.exports.excel', [
            'requests' => RequestModel::with("status","user")->where("status_id",4)->whereBetween("tanggal_request",[$this->first_date,$this->second_date])->get()
        ]);
    }
}
