<?php

namespace App\Models;

use App\Models\YearCostStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstallmentPay extends Model
{
    use HasFactory;
    protected $table = 'installment_pay';

    protected $fillable = ['yearcost_stage_id', 'percentage', 'amount', 'before', 'status'];

    public function yearcostStage()
    {
        return $this->belongsTo(YearCostStage::class);
    }
}
