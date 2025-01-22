<?php

namespace App\Models;

use App\Models\stage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class yearcost extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "yearcost";


        /**
     * Get the year that owns the yearcost_stage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function year(): BelongsTo
    {
        return $this->belongsTo(year::class, 'year_id');
    }

    public function yearcost_stage(){
        $this->hasMany(yearcost_stage::class, 'yearcost_id' );
    }
}
