<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClubCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($clubs) {
                return [
                    'id' => $clubs->id,
                    'club_id' => $clubs->id,
                    'title' => $clubs->title,
                    'description' => $clubs->description,
                    'image' =>  $clubs->clubMainLogo ? $clubs->clubMainLogo->source : "",
                    'header_logo' => $clubs->clubHeaderLogo ?  $clubs->clubHeaderLogo->source: "",
                    'status' => $clubs->status,
                ];
            })
        ];
    }
}
