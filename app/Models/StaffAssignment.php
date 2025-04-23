<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StaffAssignment
 * 
 * @property int $id
 * @property int $user_id
 * @property string $area
 * @property int|null $Campaign_id
 * @property int|null $event_id
 * @property int|null $assigned_by_id
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User|null $user
 * @property Campaign|null $campaign
 * @property Event|null $event
 *
 * @package App\Models
 */
class StaffAssignment extends Model
{
	use SoftDeletes;
	protected $table = 'staff_assignments';

	protected $casts = [
		'user_id' => 'int',
		'Campaign_id' => 'int',
		'event_id' => 'int',
		'assigned_by_id' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'area',
		'Campaign_id',
		'event_id',
		'assigned_by_id',
		'start_date',
		'end_date'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'assigned_by_id');
	}

	public function campaign()
	{
		return $this->belongsTo(Campaign::class, 'Campaign_id');
	}

	public function event()
	{
		return $this->belongsTo(Event::class);
	}
}
