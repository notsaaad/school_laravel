<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class variant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ["id"];



    function product()
    {
        return $this->belongsTo(product::class);
    }



    protected static function booted()
    {
        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order', 'asc');
        });
    }


    public function stores()
    {
        return $this->belongsToMany(warehouse::class, 'warehouse_product_variants')
            ->withPivot('product_id', 'stock')
            ->withTimestamps();
    }




    function get_vairant_in_warehouse()
    {
        return WarehouseProductVariant::whereHas("warehouseProduct", function ($q) {
            $q->whereHas("warehouse");
        })->where("variant_id", $this->id)->first();
    }


    function  canMakeOrderInThisVairant()
    {
        if ($this->product->show == "0") {
            return false;
        }


        if (($this->product->gender != auth()->user()->gender && $this->product->gender != "both") ||  $this->product->stage_id != auth()->user()->stage_id) {
            return false;
        }


        return true;
    }
}
