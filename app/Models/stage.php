<?php

namespace App\Models;

use App\Models\product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function products()
    {
        return $this->belongsToMany(product::class, 'product_stage');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_stage');
    }
}
