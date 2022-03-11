<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Productbas extends Pivot
{
    use HasFactory;
    protected $guarded =[];

    protected $table = 'productbas';

    public function user(){
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
