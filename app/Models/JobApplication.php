<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function job_application()
    {
        return $this->morphOne(File::class, 'fileable')->latest()->where('type', 'job_application');
    }

    public function job_post()
    {
        return $this->belongsTo(JobPost::class,'job_post_id');
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class,'resume_id');
    }

}
