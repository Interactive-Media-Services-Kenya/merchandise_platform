<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueProduct extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function brandambassador(){
        return $this->belongsTo(User::class, 'ba_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
