<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class busOrder extends Model
{
    use HasFactory;

    protected $guarded = [];



    protected static function booted()
    {
        parent::boot();

        static::addGlobalScope('auth', function ($builder) {
            $builder->orderBy("id", "desc");
            if (auth()->user()->role == "user") {
                $builder->where("user_id", auth()->id());
            }
        });
    }



    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }
    public function bus()
    {
        return $this->belongsTo(bus::class, 'bus_id');
    }
}
