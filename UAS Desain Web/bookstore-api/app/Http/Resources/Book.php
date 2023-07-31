<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Book extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $parent = parent::toArray($request);
        $data['categories'] = $this->categories;
        $data = array_merge($parent, $data);

        return [
            'status' => 'succes',
            'message' => 'category data',
            'data' => $data
        ];
    }
}
