<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
