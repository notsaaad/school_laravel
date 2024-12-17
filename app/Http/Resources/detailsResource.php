<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class detailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [

        "id" => $this->id,
        'discription' => $this->discription,
        "qnt" => $this->qnt ,
        "img" => path($this->product->img) ,


    ];
    }
}
