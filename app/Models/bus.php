<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class bus extends Model
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


    public function settings()
    {
        return $this->hasMany(busSetting::class, 'bus_id');
    }

    public function places()
{
    return $this->belongsToMany(place::class, 'bus_places');
}

}
