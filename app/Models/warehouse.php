<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class warehouse extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ["name", "order"];


    protected static function booted()
    {
        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    public function warehouseProducts()
    {
        return $this->hasMany(WarehouseProduct::class);
    }

    public function productsWithVariants()
    {
        return $this->hasManyThrough(
            Product::class,                   // Final model to access
            WarehouseProduct::class,          // Intermediate model
            'warehouse_id',                   // Foreign key on warehouse_products table
            'id',                              // Foreign key on products table
            'id',                              // Local key on warehouses table
            'product_id'                       // Local key on warehouse_products table
        )->with('variants');                  // Eager load variants
    }
}
