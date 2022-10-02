<?php

namespace App\Exports;

use App\Models\User;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TeamLeadersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('users')
        ->select('name', 'phone', 'email','county_id','client_id')
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
            'client_id',
        ];

    }
}
