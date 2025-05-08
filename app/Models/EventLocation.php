<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EventLocation
 * 
 * @property int $id
 * @property int $event_id
 * @property string $location_name
 * @property string|null $address
 * @property float|null $latitud
 * @property float|null $longitud
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Event $event
 *
 * @package App\Models
 */
class EventLocation extends Model
{
	use SoftDeletes;
	protected $table = 'event_locations';

	protected $casts = [
		'event_id' => 'int',
		'latitud' => 'float',
		'longitud' => 'float',
		'start_hour' => 'datetime',
		'end_hour' => 'datetime'
		
	];

	protected $fillable = [
		'event_id',
		'location_name',
		'address',
		'latitud',
		'longitud',
		'start_hour',
		'end_hour'
	];

	public function event()
	{
		return $this->belongsTo(Event::class);
	}
}
