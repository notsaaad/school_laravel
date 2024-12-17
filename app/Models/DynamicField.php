<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DynamicField extends Model
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

    public function applicationData()
    {
        return $this->hasMany(applicationData::class, 'field_id');
    }
}
