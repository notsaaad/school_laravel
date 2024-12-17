<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    use HasFactory;

    protected $guarded = ["id"];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(variant::class, 'variant_id');
    }


}
