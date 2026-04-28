<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskRequirement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'task_id',
        'skill_id',
        'required_level'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}