<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\JobPortalUser;

class JobPortalUserCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($resume) {
                return [
                    'id' => $resume->id,
                    'first_name' => $resume->first_name,
                    'middle_name' => $resume->middle_name,
                    'last_name' => $resume->last_name,
                    'email' => $resume->email,
                    'ewu_id_no' => $resume->ewu_id_no,
                    'user_rating' => $resume->userRating?$resume->userRating:"",
                    'profile_image' => $resume->resumeImage ? $resume->resumeImage->source : null,

                ];
            }),

        ];
    }
}

