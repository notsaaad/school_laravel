<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderDatail extends Model
{
    use HasFactory;

    protected $guarded = ["id"];


    protected $casts = [
        'picked_at' => "datetime",
    ];



    public function order()
    {
        return $this->belongsTo(order::class, "order_id");
    }


    public function product()
    {
        return $this->belongsTo(product::class, "product_id")->withTrashed();
    }



    public function variant()
    {
        return $this->belongsTo(variant::class, "variant_id")->withTrashed();
    }
}
