<?php

namespace App\Models;

use App\Models\User;
use App\Models\stage;
use App\Models\installment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class student_pay extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "student_pay";






    /**
     * Get the yearcost_stage that owns the student_pay
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function yearcost_stage(): BelongsTo
    {
        return $this->yearcost_stage(yearcost_stage::class, 'yearcost_stage_id');
    }



        /**
     * Get the installment that owns the student_pay
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function installment(): BelongsTo
    {
        return $this->installment(installment::class, 'installment_id');
    }




        /**
     * Get the user that owns the student_pay
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->user(User::class, 'student_id');
    }


}


