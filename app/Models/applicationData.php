<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class applicationData extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function field()
    {
        return $this->belongsTo(DynamicField::class, 'field_id')->withTrashed();
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
