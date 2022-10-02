<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection,WithHeadings
{
    //As Agency
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('users')
        ->select('name', 'phone', 'email','county_id')
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
        ];

    }
}
