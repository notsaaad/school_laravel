<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class applicationSubject extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subject()
    {
        return $this->belongsTo(testSubject::class, 'subject_id');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
