<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Task
 * 
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Collection|TaskAssignment[] $task_assignments
 *
 * @package App\Models
 */
class Task extends Model
{
	use SoftDeletes;
	protected $table = 'tasks';

	protected $casts = [
		'creator_id' => 'int'
	];

	protected $fillable = [
		'creator_id',
		'name',
		'description'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'creator_id');
	}

	public function task_assignments()
	{
		return $this->hasMany(TaskAssignment::class);
	}
}
