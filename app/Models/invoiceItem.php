<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class invoiceItem extends Model
{
    use HasFactory;


    protected $guarded = ["id"];


    function invoice(): BelongsTo
    {
        return $this->belongsTo(invoice::class);
    }

    function product(): BelongsTo
    {
        return $this->belongsTo(product::class)->withTrashed();
    }

    function variant(): BelongsTo
    {
        return $this->belongsTo(variant::class , "variant_id")->withTrashed();
    }
}
