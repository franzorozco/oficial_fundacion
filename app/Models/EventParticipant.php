<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EventParticipant
 * 
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property Carbon $registration_date
 * @property string|null $observations
 * @property string|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Event $event
 * @property User $user
 *
 * @package App\Models
 */
class EventParticipant extends Model
{
	use SoftDeletes;
	protected $table = 'event_participants';

	protected $casts = [
		'event_id' => 'int',
		'user_id' => 'int',
		'registration_date' => 'datetime'
	];

	protected $fillable = [
		'event_id',
		'user_id',
		'registration_date',
		'observations',
		'status'
	];

	public function event()
	{
		return $this->belongsTo(Event::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
