<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SystemLog
 * 
 * @property int $id
 * @property string $action
 * @property int|null $user_id
 * @property Carbon $timestamp
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class SystemLog extends Model
{
	use SoftDeletes;
	protected $table = 'system_logs';

	protected $casts = [
		'user_id' => 'int',
		'timestamp' => 'datetime'
	];

	protected $fillable = [
		'action',
		'user_id',
		'timestamp'
	];
	
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
