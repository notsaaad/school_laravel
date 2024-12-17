<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentHistory extends Model
{
    use HasFactory;


    protected $guarded = ["id"];

    

    public function auth_user()
    {
        return $this->belongsTo(user::class, "auth");
    }
}
