<?php

namespace App\Repositories;

use App\Interfaces\JobApplicationInterface;
use App\Models\JobApplication;


class JobApplicationRepository extends BaseRepository implements JobApplicationInterface
{
    public function __construct(JobApplication $model)
    {
        $this->model = $model;
    }
}
