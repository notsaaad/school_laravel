<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class order extends Model
{
    use HasFactory;

    protected $guarded = ["id"];


    protected $casts = [
        'logs' => 'array',
        'picked_at' => "datetime",
    ];






    protected static function booted()
    {
        parent::boot();

        static::addGlobalScope('auth', function ($builder) {
            $builder->orderBy("id", "desc");
            if (auth()->user()->role == "user") {
                $builder->where("user_id", auth()->id());
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }
    public function package()
    {
        return $this->belongsTo(package::class, 'package_id');
    }

    public function details()
    {
        return $this->hasMany(orderDatail::class, 'order_id');
    }



    public function scopeWithoutTransfers($query)
    {
        return $query->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('transfers')
                ->join('transfer_orders', 'transfers.id', '=', 'transfer_orders.transfer_id')
                ->whereColumn('orders.id', 'transfer_orders.order_id');
        });
    }





    public function payment_history()
    {
        return $this->hasMany(paymentHistory::class, 'order_id');
    }



    // order Data


      public function getBasePrice()
      {
          if ($this->type === 'package') {
              return $this->price;
          }

          return $this->details->sum(fn($d) => $d->sell_price * $d->qnt);
      }

      public function getFees()
      {
          return $this->getBasePrice() * ($this->service_expenses / 100);
      }

      public function getTotalPrice()
      {
          return $this->getBasePrice() + $this->getFees();
      }


    public function amount_received()
    {
        return $this->payment_history->sum("amount");
    }

    // function Get_Next_Status()
    // {

    //     $status = [
    //         "return_requested" => ["paid", "returned"],
    //     ];

    //     return $status[$this->status] ?? [];
    // }
}
