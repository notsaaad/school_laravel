<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class transfer extends Model
{
    use HasFactory;

    protected $guarded = [];


    function enteredByUser(): BelongsTo
    {
        return $this->belongsTo(user::class, "enteredBy")->withTrashed();
    }

    function paidByUser(): BelongsTo
    {
        return $this->belongsTo(user::class, "paidBy")->withTrashed();
    }
    function confirmByUser(): BelongsTo
    {
        return $this->belongsTo(user::class, "confirmBy");
    }

    public function orders()
    {
        return $this->belongsToMany(order::class, 'transfer_orders', 'transfer_id', 'order_id');
    }

    protected static function booted(): void
    {
        static::addGlobalScope(function (Builder $builder) {
            $builder->orderBy("id", "desc");
        });
    }
}
