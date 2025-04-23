<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AttachedFile
 * 
 * @property int $id
 * @property int $user_id
 * @property string $file_path
 * @property string $file_name
 * @property int|null $file_size
 * @property string|null $file_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class AttachedFile extends Model
{
	use SoftDeletes;
	protected $table = 'attached_files';

	protected $casts = [
		'user_id' => 'int',
		'file_size' => 'int'
	];

	protected $fillable = [
		'user_id',
		'file_path',
		'file_name',
		'file_size',
		'file_type'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
