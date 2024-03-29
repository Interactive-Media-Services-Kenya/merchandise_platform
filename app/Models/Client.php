<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['name','email','phone', 'address','created_by'];

    public function brands(){
        return $this->hasMany(Brand::class,'client_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
