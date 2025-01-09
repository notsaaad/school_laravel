<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class testSubject extends Model
{
    use HasFactory;


    use SoftDeletes;

    protected $guarded = [];


    public function test()
    {
        return $this->belongsTo(test::class, 'test_id');
    }

}
