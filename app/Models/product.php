<?php

namespace App\Models;

use App\Models\StockTransfer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = ["id"];


    public function stages()
    {
        return $this->belongsToMany(stage::class, 'product_stage')->withTrashed();
    }

    public function variants()
    {
        return $this->hasMany(variant::class, 'product_id');
    }


public function packages()
{
    return $this->belongsToMany(Package::class, 'package_products');
}


    public function warehouses()
    {
        return $this->belongsToMany(warehouse::class, 'warehouse_products')
            ->withTimestamps();
    }

        public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }



}
