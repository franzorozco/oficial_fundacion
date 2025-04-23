<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Profile
 * 
 * @property int $id
 * @property int $user_id
 * @property string|null $bio
 * @property string|null $document_number
 * @property string|null $photo
 * @property Carbon|null $birthdate
 * @property string|null $skills
 * @property string|null $interests
 * @property string|null $availability_days
 * @property string|null $availability_hours
 * @property string|null $location
 * @property bool|null $transport_available
 * @property string|null $experience_level
 * @property string|null $physical_condition
 * @property string|null $preferred_tasks
 * @property string|null $languages_spoken
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Profile extends Model
{
	use SoftDeletes;
	protected $table = 'profiles';

	protected $casts = [
		'user_id' => 'int',
		'birthdate' => 'datetime',
		'transport_available' => 'bool'
	];

	protected $fillable = [
		'user_id',
		'bio',
		'document_number',
		'photo',
		'birthdate',
		'skills',
		'interests',
		'availability_days',
		'availability_hours',
		'location',
		'transport_available',
		'experience_level',
		'physical_condition',
		'preferred_tasks',
		'languages_spoken'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
