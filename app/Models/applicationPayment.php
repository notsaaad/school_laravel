<?php

namespace App\Models;

use App\Models\User;
use App\Models\application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class applicationPayment extends Model
{
    protected $guarded = [];
    protected $table = 'application_payment';
    use HasFactory;


    public function application(){
        return $this->belongsTo(application::class, 'application_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
