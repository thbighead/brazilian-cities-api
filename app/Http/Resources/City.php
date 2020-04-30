<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class City extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $is_show = $request->routeIs('*.show');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => new State($this->whenLoaded('state')),
            'created_at' => $this->when($is_show, $this->created_at),
            'updated_at' => $this->when($is_show, $this->updated_at),
        ];
    }
}
