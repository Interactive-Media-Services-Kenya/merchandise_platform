<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
   // protected $fillable = ['batch_code'];
    protected $guarded = [];
    public function teamleader(){
        return $this->belongsTo(User::class, 'tl_id_accept');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
