<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

/**
 * Class DonationRequest
 * 
 * @property int $id
 * @property int $applicant_user__id
 * @property int|null $user_in_charge_id
 * @property int $donation_id
 * @property Carbon $request_date
 * @property string|null $notes
 * @property string|null $state
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User|null $user
 * @property Donation $donation
 * @property Collection|DonationRequestDescription[] $donation_request_descriptions
 *
 * @package App\Models
 */
class DonationRequest extends Model
{
	use SoftDeletes;
	protected $table = 'donation_requests';

	protected $casts = [
        'applicant_user_id' => 'int',  // Cambiar el nombre del campo
        'user_in_charge_id' => 'int',
        'donation_id' => 'int',
        'request_date' => 'datetime',
    ];

	protected $fillable = [
        'applicant_user_id',  // Cambiar el nombre del campo
        'user_in_charge_id',
        'donation_id',
        'request_date',
        'notes',
        'state',
    ];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_in_charge_id');
	}

	public function donation()
	{
		return $this->belongsTo(Donation::class);
	}

	public function donation_request_descriptions()
	{
		return $this->hasMany(DonationRequestDescription::class);
	}
}
