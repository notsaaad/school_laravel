<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class applicationFee extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];

    public function stage()
    {
        return $this->belongsTo(stage::class, 'stage_id')->withTrashed();
    }
    public function year()
    {
        return $this->belongsTo(year::class, 'year_id');
    }


}
