<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class details_display extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'discription' => $this->discription,
            'qnt' => $this->qnt,
            'place' => $this->variant_id != null ? $this->variant->place : $this->product->place
        ];
    }
}
