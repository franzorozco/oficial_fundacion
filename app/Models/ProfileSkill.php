<?php
class ProfileSkill extends Model
{
    protected $fillable = [
        'profile_id',
        'skill_id',
        'level'
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}