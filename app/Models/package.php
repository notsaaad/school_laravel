<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class package extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = ["id"];


    protected static function booted()
    {
        static::addGlobalScope('show', function ($builder) {
            if (auth()->user()->role == "1") {
                $builder->where("show", "1");
            }
        });
    }


    public function stage()
    {
        return $this->belongsTo(stage::class, 'stage_id')->withTrashed();
    }

    public function products()
    {
        return $this->belongsToMany(product::class, "package_products");
    }
    public function student_products()
    {
        return $this->belongsToMany(product::class, "package_products")->where("show", "1");
    }
}
