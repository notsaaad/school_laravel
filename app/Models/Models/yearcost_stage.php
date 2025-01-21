<?php

namespace App\Models\Models;

use App\Models\Models\yearcost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class yearcost_stage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "yearcost_stage";


    public function yearcost(){
        $this->belongsToMany(yearcost::class, 'yearcost_id');
    }
}
