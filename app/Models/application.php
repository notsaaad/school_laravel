<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class application extends Model
{
    use HasFactory;


    use SoftDeletes;

    protected $guarded = [];


    public function specialStatus()
    {
        return $this->belongsTo(definition::class, 'specialStatus')->first();
    }

    public function custom_status()
    {
        return $this->belongsTo(customState::class, 'custom_status_id')->withTrashed()->first();
    }
    public function discountType()
    {
        return $this->belongsTo(definition::class, 'discountType')->first();
    }
    public function referralSource()
    {
        return $this->belongsTo(definition::class, 'referralSource')->first();
    }

    public function place()
    {
        return $this->belongsTo(place::class, 'place_id')->first();
    }




    public function fees()
    {
        return $this->belongsTo(applicationFee::class, 'fees_id')->withTrashed();
    }


    public function subjects()
    {
        return $this->belongsToMany(testSubject::class, 'application_subjects', 'application_id', 'subject_id');
    }


    public function application_subjects()
    {
        return $this->hasMany(applicationSubject::class);
    }

    public function stage()
    {
        return $this->belongsTo(stage::class, 'stage_id')->withTrashed();
    }
    public function year()
    {
        return $this->belongsTo(year::class, 'year_id');
    }



    public function applicationData()
    {
        return $this->hasMany(applicationData::class, 'application_id');
    }
}
