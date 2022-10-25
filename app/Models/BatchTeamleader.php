<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchTeamleader extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $table = 'batch_teamleaders';

    public function batchTeamleader(){
        return $this->belongsToMany(Product::class,'batch_tl_id');
    }
}
