<?php

namespace App\Models;

use App\Models\YearCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class System extends Model
{
    use HasFactory;
    protected $table = 'system';

    protected $fillable = ['name'];

    public function yearCosts()
    {
        return $this->hasMany(YearCost::class);
    }
}
