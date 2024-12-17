<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class fee extends Model
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


    public function stage()
    {
        return $this->belongsTo(stage::class, 'stage_id')->withTrashed();
    }
    public function year()
    {
        return $this->belongsTo(year::class, 'year_id');
    }

    function items()
    {
        return $this->hasMany(feesItem::class, "fee_id");
    }

    function payments()
    {
        return $this->morphMany(feesPayment::class, "payable");
    }
    function userPayments($userId)
    {
        return $this->morphMany(feesPayment::class, "payable")->where("user_id", $userId);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'fees_students', 'fee_id', 'user_id');
    }
}
