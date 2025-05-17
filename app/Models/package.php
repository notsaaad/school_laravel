<?php

namespace App\Models;

use App\Models\stage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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


    public function stages()
    {
        return $this->belongsToMany(Stage::class, 'package_stage', 'package_id', 'stage_id')->withTrashed();
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
