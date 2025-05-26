<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TaskAssignment
 * 
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property int $supervisor
 * @property string|null $status
 * @property Carbon $assigned_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Task $task
 *
 * @package App\Models
 */
class TaskAssignment extends Model
{
	use SoftDeletes;
	protected $table = 'task_assignments';

	protected $casts = [
		'task_id' => 'int',
		'user_id' => 'int',
		'supervisor' => 'int',
		'assigned_at' => 'datetime'
	];

	protected $fillable = [
		'task_id',
		'user_id',
		'supervisor',
		'status',
		'assigned_at',
		'notes'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function task()
	{
		return $this->belongsTo(Task::class);
	}
	
	public function donationRequest()
	{
		return $this->belongsTo(DonationRequest::class, 'donation_request_id');
	}

}
