<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = 'skills_catalog';

    protected $fillable = ['name', 'description'];
}   