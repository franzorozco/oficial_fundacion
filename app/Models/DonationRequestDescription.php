<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DonationRequestDescription
 * 
 * @property int $id
 * @property int $donation_request_id
 * @property string $recipient_name
 * @property string|null $recipient_address
 * @property string|null $recipient_contact
 * @property string|null $recipient_type
 * @property string|null $reason
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $extra_instructions
 * @property string|null $supporting_documents
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property DonationRequest $donation_request
 *
 * @package App\Models
 */
class DonationRequestDescription extends Model
{
	use SoftDeletes;
	protected $table = 'donation_request_descriptions';

	protected $casts = [
		'donation_request_id' => 'int',
		'latitude' => 'float',
		'longitude' => 'float'
	];

	protected $fillable = [
		'donation_request_id',
		'recipient_name',
		'recipient_address',
		'recipient_contact',
		'recipient_type',
		'reason',
		'latitude',
		'longitude',
		'extra_instructions',
		'supporting_documents'
	];

	public function donation_request()
	{
		return $this->belongsTo(DonationRequest::class);
	}

	public function donationRequest()
	{
		return $this->belongsTo(DonationRequest::class);
	}


}
