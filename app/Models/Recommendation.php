<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Recommendation
 * 
 * @property int $id
 * @property int $user_id
 * @property int $action_id
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property AgentAction $agent_action
 *
 * @package App\Models
 */
class Recommendation extends Model
{
	use SoftDeletes;
	protected $table = 'recommendations';

	protected $casts = [
		'user_id' => 'int',
		'action_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'action_id',
		'description'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function agent_action()
	{
		return $this->belongsTo(AgentAction::class, 'action_id');
	}
}
