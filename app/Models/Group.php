<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function profileImage()
    {
        return $this->morphOne(File::class, 'fileable')->latest()->where('type', 'group-profile');
    }

    public function backgroundImage()
    {
        return $this->morphOne(File::class, 'fileable')->latest()->where('type', 'group-background');
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'user_id', 'id');
    }

    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class);
    }
}
