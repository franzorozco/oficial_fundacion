<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DonationItemPhoto
 * 
 * @property int $id
 * @property int $donation_item_id
 * @property string $photo_url
 * @property string|null $caption
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property DonationItem $donation_item
 *
 * @package App\Models
 */
class DonationItemPhoto extends Model
{
	use SoftDeletes;
	protected $table = 'donation_item_photos';

	protected $casts = [
		'donation_item_id' => 'int'
	];

	protected $fillable = [
		'donation_item_id',
		'photo_url',
		'caption'
	];

	public function donation_item()
	{
		return $this->belongsTo(DonationItem::class);
	}
}
