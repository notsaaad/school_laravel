<?php

namespace App\Models;

use App\Models\stage;
use App\Models\YearCost;
use App\Models\StudentPay;
use App\Models\InstallmentPay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YearCostStage extends Model
{
    use HasFactory;
    protected $table = 'yearcost_stages';

    protected $fillable = ['yearcost_id', 'stage_id', 'book', 'cash', 'installment'];

    public function yearcost()
    {
        return $this->belongsTo(YearCost::class);
    }

    public function stage()
    {
        return $this->belongsTo(stage::class);
    }

    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPay::class);
    }

    public function studentPayments()
    {
        return $this->hasMany(StudentPay::class);
    }
}
