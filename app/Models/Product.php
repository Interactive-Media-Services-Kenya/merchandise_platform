<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    //protected $fillable = ['product_code','deleted_at','user_id','category_id','batch_id','assigned_to', 'client_id'];
    protected $guarded = [];
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function assign(){
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function productBa(){
        return $this->hasOne(Productbas::class, 'product_id');
    }
}
