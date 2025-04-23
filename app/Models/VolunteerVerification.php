<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VolunteerVerification
 * 
 * @property int $id
 * @property int $user_id
 * @property int $user_resp_id
 * @property string|null $document_type
 * @property string|null $document_url
 * @property string|null $name_document
 * @property string|null $status
 * @property string|null $coment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class VolunteerVerification extends Model
{
	use SoftDeletes;
	protected $table = 'volunteer_verifications';

	protected $casts = [
		'user_id' => 'int',
		'user_resp_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'user_resp_id',
		'document_type',
		'document_url',
		'name_document',
		'status',
		'coment'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_resp_id');
	}
}
