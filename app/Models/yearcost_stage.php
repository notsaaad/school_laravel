<?php

namespace App\Models;

use App\Models\year;
use App\Models\stage;
use App\Models\installment;

use App\Models\Models\yearcost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class yearcost_stage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "yearcost_stage";


    /**
     * Get the yearcost that owns the yearcost_stage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function yearcost(): BelongsTo
    {
        return $this->belongsTo(yearcost::class, 'yearcost_id');
    }

    /**
     * Get the stage that owns the stage_stage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(stage::class, 'stage_id');
    }

    /**
     * Get all of the installment for the yearcost_stage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function installment(): HasMany
    {
        return $this->hasMany(installment::class, 'yearcost_stage_id');
    }

    /**
     * Get all of the student_pay for the yearcost_stage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function student_pay(): HasMany
    {
        return $this->hasMany(student_pay::class, 'yearcost_stage_id');
    }


}
