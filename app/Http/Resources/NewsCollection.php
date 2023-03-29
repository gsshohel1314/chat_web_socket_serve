<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NewsCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($news) {
                return [
                    'id' => $news->id,
                    'categories' => $news->categories,
                    'title' => $news->title,
                    'slug' => $news->slug,
                    'description' => $news->description,
                    'image' => $news->news ? $news->news->source : "",
                    'order_place' => $news->order_place,
                    'status' => $news->status,
                    'is_approved' => $news->is_approved,
                ];
            })
        ];
    }
}
