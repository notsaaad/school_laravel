<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class stage extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ["name"];

    protected static function booted()
    {
        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order', 'asc');
        });
    }



    /**
     * Get all of the yearcost_stage for the stage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function yearcost_stage(): HasMany
    {
        return $this->hasMany(Comment::class, 'stage_id');
    }


    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_stage');
    }
}
