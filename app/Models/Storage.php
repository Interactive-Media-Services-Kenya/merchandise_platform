<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function batches(){
        return $this->hasMany(Batch::class);
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }
}
