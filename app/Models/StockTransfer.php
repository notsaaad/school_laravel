<?php

namespace App\Models;

use App\Models\product;
use App\Models\variant;
use App\Models\warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockTransfer extends Model
{
    use HasFactory;
        protected $fillable = [
        'from_warehouse_id',
        'to_warehouse_id',
        'product_id',
        'variant_id',
        'quantity'
    ];

        public function fromWarehouse()
    {
        return $this->belongsTo(warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(warehouse::class, 'to_warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(product::class);
    }

    public function variant()
    {
        return $this->belongsTo(variant::class);
    }
}
