<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $perPage = 20;

    protected $fillable = [
        'name',
        'description',
        'location',
        'latitude',
        'longitude',
        'required_days',
        'required_hours',
        'creator_id',
        'requires_transport'
    ];

    // 🔹 Usuario creador
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // 🔹 Asignaciones
    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class, 'task_id');
    }

    // 🔹 HABILIDADES REQUERIDAS (CLAVE 🔥)
    public function requirements()
    {
        return $this->hasMany(TaskRequirement::class);
    }

    // 🔹 Relación directa con skills (mucho más útil)
    public function skills()
    {
        return $this->belongsToMany(
            Skill::class,
            'task_requirements',
            'task_id',
            'skill_id'
        )->withPivot('required_level');
    }



}