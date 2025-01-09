<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class feesItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order', 'asc');
        });
    }


    function payments()
    {
        return $this->morphMany(feesPayment::class, "payable");
    }

    function userPayments($userId)
    {
        return $this->morphMany(feesPayment::class, "payable")->where("user_id", $userId);
    }
}
