<?php

namespace App\Models\Models;

use App\Models\stage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class yearcost extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "yearcost";


    public function yearcost_stage(){
        $this->hasMany(yearcost_stage::class, 'yearcost_id' );
    }
}
