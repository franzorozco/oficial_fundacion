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
 * Class DonationType
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|DonationItem[] $donation_items
 *
 * @package App\Models
 */
class DonationType extends Model
{
	use SoftDeletes;
	protected $table = 'donation_types';

	protected $fillable = [
		'name',
		'description'
	];

	public function donation_items()
	{
		return $this->hasMany(DonationItem::class);
	}
}
