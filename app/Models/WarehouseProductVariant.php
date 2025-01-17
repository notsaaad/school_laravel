<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseProductVariant extends Model
{
    // تحديد الجدول المرتبط بالنموذج
    protected $table = 'warehouse_product_variants';

    // السماح بالحقول القابلة للتعبئة
    protected $fillable = ['warehouse_product_id', 'variant_id', 'stock'];

    public function warehouseProduct()
    {
        return $this->belongsTo(WarehouseProduct::class);
    }

    public function variant()
    {
        return $this->belongsTo(variant::class);
    }
}
