<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class product extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = ["id"];


    public function stage()
    {
        return $this->belongsTo(stage::class, 'stage_id')->withTrashed();
    }

    public function variants()
    {
        return $this->hasMany(variant::class, 'product_id');
    }





    public function warehouses()
    {
        return $this->belongsToMany(warehouse::class, 'warehouse_products')
            ->withTimestamps();
    }




}
