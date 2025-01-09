<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feesPayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function payable()
    {
        return $this->morphTo()->withTrashed();
    }




    public function auth()
    {
        return $this->belongsTo(user::class, 'auth_id');
    }
}
