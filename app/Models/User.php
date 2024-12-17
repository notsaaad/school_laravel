<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ["id"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_settings' => 'array'

        ];
    }



    public function details()
    {
        return $this->hasOne(studentDetail::class, "student_id");
    }


    public function HisRole()
    {
        return $this->belongsTo(role::class, 'role_id');
    }

    public function stage()
    {
        return $this->belongsTo(stage::class, 'stage_id')->withTrashed();
    }

    function fees()
    {
        return fee::whereHas("items")->with(["items" => function ($q) {
            $q->with("payments");
        }])->where(function ($query) {
            $query->where('stage_id', $this->stage_id)
                ->orWhereHas('students', function ($query) {
                    $query->where('user_id',  $this->id);
                });
        })
            ->where('enable', '1')
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->where('end_at', '>', Carbon::now())
                    ->orWhere('end_at', null);
            })
        ;
    }


    function All_fees()
    {
        return fee::whereHas("items")->withTrashed()->with(["items" => function ($q) {
            $q->withTrashed()->with("payments");
        }])->where(function ($query) {
            $query->where('stage_id', $this->stage_id)
                ->orWhereHas('students', function ($query) {
                    $query->where('user_id',  $this->id);
                });
        });
    }

    public function payments()
    {
        return $this->hasMany(feesPayment::class)->orderBy("id", "desc");
    }
}