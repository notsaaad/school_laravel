<?php

namespace App\Models;

use App\Models\User;
use App\Models\Installment;
use App\Models\YearCostStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentPay extends Model
{
    use HasFactory;
      protected $table = 'student_pay';

    protected $fillable = [
        'yearcost_stage_id',
        'installment_id',
        'student_id',
        'type',
        'method',
        'amount',
        'status'
    ];

    public function yearcostStage()
    {
        return $this->belongsTo(YearCostStage::class);
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class);
    }
}
