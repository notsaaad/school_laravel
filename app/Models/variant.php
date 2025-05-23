<?php

namespace App\Models;

use App\Models\WarehouseProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return WarehouseProductVariant::where('variant_id', $this->id)
            ->whereHas('warehouseProduct', function ($q) {
                $q->whereHas('warehouse', function ($w) {
                    $w->where('name', '!=', 'مخزن رئيسي');
                });
            })
            ->sum('stock');
    }




      public function warehouseProducts()
      {
          return $this->hasManyThrough(
              WarehouseProduct::class,
              WarehouseProductVariant::class,
              'variant_id',      // Foreign key on WarehouseProductVariant
              'id',              // Foreign key on WarehouseProduct
              'id',              // Local key on Variant
              'warehouse_product_id' // Local key on WarehouseProductVariant
          );
      }

      public function warehouses()
      {
          return $this->warehouseProducts->map(function ($wp) {
              return $wp->warehouse;
          })->unique('id');
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

    public function warehouseProductVariants()
{
    return $this->hasMany(WarehouseProductVariant::class);
}
}
