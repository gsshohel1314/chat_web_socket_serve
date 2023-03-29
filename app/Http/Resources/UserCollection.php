<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($users) {
                return [
                    'id' => $users->id,
                    'name' => $users->name,
                    'bn_name' => $users->bn_name,
                    'username' => $users->username,
                    'mobile' => $users->mobile,
                    'email' => $users->email,
                    'status' => $users->status,
                ];
            })
        ];
    }
}