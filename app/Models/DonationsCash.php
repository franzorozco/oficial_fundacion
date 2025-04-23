<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DonationsCash
 * 
 * @property int $id
 * @property int|null $donor_id
 * @property int|null $external_donor_id
 * @property float $amount
 * @property string|null $method
 * @property Carbon $donation_date
 * @property int|null $campaign_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property ExternalDonor|null $external_donor
 * @property User|null $user
 * @property Campaign|null $campaign
 *
 * @package App\Models
 */
class DonationsCash extends Model
{
	use SoftDeletes;
	protected $table = 'donations_cash';

	protected $casts = [
		'donor_id' => 'int',
		'external_donor_id' => 'int',
		'amount' => 'float',
		'donation_date' => 'datetime',
		'campaign_id' => 'int'
	];

	protected $fillable = [
		'donor_id',
		'external_donor_id',
		'amount',
		'method',
		'donation_date',
		'campaign_id'
	];

	public function external_donor()
	{
		return $this->belongsTo(ExternalDonor::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'donor_id');
	}

	public function campaign()
	{
		return $this->belongsTo(Campaign::class);
	}
}
