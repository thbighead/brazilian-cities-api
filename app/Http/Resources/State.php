<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class State extends JsonResource
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
            'acronym' => $this->acronym,
            'name' => $this->name,
            'cities' => City::collection($this->whenLoaded('cities')),
            'created_at' => $this->when($is_show, $this->created_at),
            'updated_at' => $this->when($is_show, $this->updated_at),
        ];
    }
}
