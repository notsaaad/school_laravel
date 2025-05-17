<?php

namespace App\Models;

use App\Models\YearCost;
use App\Models\StudentPay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Installment extends Model
{
    use HasFactory;
    protected $table = 'installment';

    protected $fillable = ['yearcost_id', 'per'];

    public function yearcost()
    {
        return $this->belongsTo(YearCost::class);
    }

    public function studentPayments()
    {
        return $this->hasMany(StudentPay::class);
    }
}
