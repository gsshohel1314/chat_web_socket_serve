<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ResourceCccCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($resources) {
                return [
                    'id' => $resources->id,
                    'title' => $resources->title,
                    'slug' => $resources->slug,
                    'description' => $resources->description,
                    'order_place' => $resources->order_place,
                    'icon_class' => $resources->icon_class,
                    'status' => $resources->status,
                ];
            })
        ];
    }
}
