<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

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

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function colorProduct(){
        return $this->belongsTo(Color::class, 'color');
    }
    public function sizeProduct(){
        return $this->belongsTo(Size::class, 'size');
    }

    public function productBa(){
        return $this->hasOne(Productbas::class, 'product_id');
    }
    public function ownerProduct(){
        return $this->belongsTo(Productbas::class, 'product_id');
    }
    public function batchBA(){
        return $this->belongsTo(BatchBrandambassador::class,'batch_ba_id');
    }
    public function batchTL(){
        return $this->belongsTo(BatchTeamleader::class,'batch_tl_id');
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function customer(){
        return $this->hasOne(Customer::class);
    }
    public function issueProduct(){
        return $this->hasOne(IssueProduct::class,'product_id');
    }

    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }

}
