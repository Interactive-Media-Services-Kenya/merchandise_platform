<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function county(){
        return $this->belongsTo(County::class,'county_id');
    }
}
