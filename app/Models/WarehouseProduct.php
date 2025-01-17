<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseProduct extends Model
{
    protected $table = 'warehouse_products';

    protected $fillable = ['warehouse_id', 'product_id'];


    public function warehouse()
    {
        return $this->belongsTo(warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(product::class);
    }

    public function variants()
    {
        return $this->hasMany(WarehouseProductVariant::class);
    }
}
