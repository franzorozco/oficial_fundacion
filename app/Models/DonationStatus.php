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
 * Class DonationStatus
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Donation[] $donations
 *
 * @package App\Models
 */
class DonationStatus extends Model
{
	use SoftDeletes;
	protected $table = 'donation_statuses';

	protected $fillable = [
		'name',
		'description'
	];

	public function donations()
	{
		return $this->hasMany(Donation::class, 'status_id');
	}
}
