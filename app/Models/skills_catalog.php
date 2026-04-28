<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = 'skills_catalog';

    protected $fillable = ['name', 'description'];

    public function profiles()
    {
        return $this->belongsToMany(
            Profile::class,
            'profile_skills'
        )->withPivot('level');
    }

    public function tasks()
    {
        return $this->belongsToMany(
            Task::class,
            'task_requirements'
        )->withPivot('required_level');
    }
}