<?php

namespace App\Exports;

use App\Models\User;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BasExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('users')
        ->select('name', 'phone', 'email','teamleader_id','county_id')
        ->take(0)
        ->get();
    }


    public function headings(): array
    {

        return [
            'name',
            'email',
            'phone',
            'county_id',
            'teamleader_id',
        ];

    }
}
