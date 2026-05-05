<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SkillsCatalog
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @property ProfileSkill[] $profileSkills
 * @property TaskRequirement[] $taskRequirements
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SkillsCatalog extends Model
{
    
    protected $table = 'skills_catalog';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profileSkills()
    {
        return $this->hasMany(\App\Models\ProfileSkill::class, 'id', 'skill_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskRequirements()
    {
        return $this->hasMany(\App\Models\TaskRequirement::class, 'id', 'skill_id');
    }
    
}
