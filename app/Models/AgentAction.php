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
 * Class AgentAction
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Recommendation[] $recommendations
 *
 * @package App\Models
 */
class AgentAction extends Model
{
	use SoftDeletes;
	protected $table = 'agent_actions';

	protected $fillable = [
		'name',
		'description'
	];

	public function recommendations()
	{
		return $this->hasMany(Recommendation::class, 'action_id');
	}
}
