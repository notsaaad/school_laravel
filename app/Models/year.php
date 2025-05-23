<?php

namespace App\Models;


use App\Models\YearCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class year extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ["name", "order"];


    protected static function booted()
    {
        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order', 'asc');
        });
    }


    public function yearcost()
    {
        return $this->hasMany(YearCost::class, 'year_id');
    }
}
