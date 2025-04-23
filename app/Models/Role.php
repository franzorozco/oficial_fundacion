<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role
 * 
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Role extends SpatieRole
{
    use SoftDeletes;

    protected $table = 'roles';
    public $incrementing = false;

    protected $casts = [
        'id' => 'int'
    ];

    protected $fillable = [
        'id',
        'name',
        'guard_name'
    ];
}
