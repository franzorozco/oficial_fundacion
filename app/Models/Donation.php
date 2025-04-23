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
 * Class Donation
 * 
 * @property int $id
 * @property int|null $external_donor_id
 * @property int|null $user_id
 * @property int $received_by_id
 * @property int $status_id
 * @property int|null $during_campaign_id
 * @property Carbon $donation_date
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property ExternalDonor|null $external_donor
 * @property DonationStatus $donation_status
 * @property Campaign|null $campaign
 * @property Collection|DonationItem[] $donation_items
 * @property Collection|DonationRequest[] $donation_requests
 *
 * @package App\Models
 */
class Donation extends Model
{
	use SoftDeletes;
	protected $table = 'donations';

	protected $casts = [
		'external_donor_id' => 'int',
		'user_id' => 'int',
		'received_by_id' => 'int',
		'status_id' => 'int',
		'during_campaign_id' => 'int',
		'donation_date' => 'datetime'
	];

	protected $fillable = [
		'external_donor_id',
		'user_id',
		'received_by_id',
		'status_id',
		'during_campaign_id',
		'donation_date',
		'notes'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'received_by_id');
	}

	public function external_donor()
	{
		return $this->belongsTo(ExternalDonor::class);
	}

	public function donation_status()
	{
		return $this->belongsTo(DonationStatus::class, 'status_id');
	}

	public function campaign()
	{
		return $this->belongsTo(Campaign::class, 'during_campaign_id');
	}

	public function donation_items()
	{
		return $this->hasMany(DonationItem::class);
	}

	public function donation_requests()
	{
		return $this->hasMany(DonationRequest::class);
	}
}
