<?php

namespace App\Http\Resources;
use App\Models\JobCategory;
use App\Models\JobSubCategory;


use Illuminate\Http\Resources\Json\ResourceCollection;

class JobPostCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($jobpost) {
                return [
                    'id' => $jobpost->id,
                    'job_category_id' => $jobpost->job_category_id,
                    'job_sub_category_id' => $jobpost->job_sub_category_id,
                    'division_id' => $jobpost->address->division_id,
                    'job_title' => $jobpost->job_title,
                    'job_code' => $jobpost->job_code,
                    'company_name' => $jobpost->company_name,
                    'company_details' => $jobpost->company_details,
                    'company_address' => $jobpost->company_address,
                    'no_of_vacancies' => $jobpost->no_of_vacancies,
                    'employment_status' => $jobpost->employment_status,
                    'resume_receiver_email' => $jobpost->resume_receiver_email,
                    'job_responsibilities' => $jobpost->job_responsibilities,
                    'job_workplace' => $jobpost->job_workplace,
                    'min_salary' => $jobpost->min_salary,
                    'max_salary' => $jobpost->max_salary,
                    'festival_bonuses' => $jobpost->festival_bonuses,
                    'gender' => $jobpost->gender,
                    'min_experience' => $jobpost->min_experience,
                    'min_academic_level' => $jobpost->min_academic_level,
                    'status' => $jobpost->status,
                    'job_publish_date' => $jobpost->job_publish_date,
                    'job_deadline' => $jobpost->job_deadline,
                    'is_approved' => $jobpost->is_approved,
                    'job_applications' => $jobpost->job_applications->count(),

                ];
            }),
            'jobcategories' => JobCategory::query()->get(),
            'jobsubcategories' => JobSubCategory::query()->get(),
        ];
    }
}
