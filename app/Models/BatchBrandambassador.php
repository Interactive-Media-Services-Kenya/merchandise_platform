<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchBrandambassador extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $table = 'batch_brandambassadors';

    public function batchBa(){
        return $this->hasMany(Product::class,'batch_ba_id');
    }
}
