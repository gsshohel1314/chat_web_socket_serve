<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkshopCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($workshops) {
                return [
                    'id' => $workshops->id,
                    'title' => $workshops->title,
                    'slug' => $workshops->slug,
                    'description' => $workshops->description,
                    'start_date' => $workshops->start_date,
                    'end_date' => $workshops->end_date,
                    'status' => $workshops->status,
                ];
            })
        ];
    }
}
