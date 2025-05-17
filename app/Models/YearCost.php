<?php

namespace App\Models;

use App\Models\year;
use App\Models\System;
use App\Models\Installment;
use App\Models\YearCostStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YearCost extends Model
{
    use HasFactory;
        protected $table = 'yearcost';

    protected $fillable = ['year_id', 'system_id', 'installment_count'];

    public function stages()
    {
        return $this->hasMany(YearCostStage::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function year()
    {
        return $this->belongsTo(year::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }
}
