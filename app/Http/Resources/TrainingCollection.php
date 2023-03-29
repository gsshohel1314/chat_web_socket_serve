<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TrainingCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($trainings) {
                return [
                    'id' => $trainings->id,
                    'job_category_id' => $trainings->job_category_id,
                    'title' => $trainings->title,
                    'slug' => $trainings->slug,
                    'description' => $trainings->description,
                    'duration_in_days' => $trainings->duration_in_days,
                    'status' => $trainings->status,
                ];
            })
        ];
    }
}
