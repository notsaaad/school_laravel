<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class invoice extends Model
{
    use HasFactory;
    use SoftDeletes;




    protected $guarded = ["id"];


    protected $casts = [
        'date' => "datetime",
    ];

    function warehouse()
    {
        return $this->belongsTo(warehouse::class, 'warehouseId')->withTrashed();
    }

    function items()
    {
        return $this->hasMany(invoiceItem::class, 'invoice_id')->orderBy("id", "desc");
    }
}
