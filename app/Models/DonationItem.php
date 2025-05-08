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
 * Class DonationItem
 * 
 * @property int $id
 * @property int $donation_id
 * @property int $donation_type_id
 * @property string $item_name
 * @property int|null $quantity
 * @property string|null $unit
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Donation $donation
 * @property DonationType $donation_type
 * @property Collection|DonationItemPhoto[] $donation_item_photos
 *
 * @package App\Models
 */
class DonationItem extends Model
{
	use SoftDeletes;
	protected $table = 'donation_items';

	protected $casts = [
		'donation_id' => 'int',
		'donation_type_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'donation_id',
		'donation_type_id',
		'item_name',
		'quantity',
		'unit',
		'description'
	];

	public function donation()
	{
		return $this->belongsTo(Donation::class);
	}

	public function donation_type()
	{
		return $this->belongsTo(DonationType::class, 'donation_type_id');
	}

	public function donation_item_photos()
	{
		return $this->hasMany(DonationItemPhoto::class);
	}
}
