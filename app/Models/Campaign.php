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
 * Class Campaign
 * 
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property Carbon|null $start_hour
 * @property Carbon|null $end_hour
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Collection|CampaignFinance[] $campaign_finances
 * @property Collection|Donation[] $donations
 * @property Collection|DonationsCash[] $donations_cashes
 * @property Collection|Event[] $events
 * @property Collection|StaffAssignment[] $staff_assignments
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class Campaign extends Model
{
	use SoftDeletes;
	protected $table = 'campaigns';

	protected $casts = [
		'creator_id' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
	];

	protected $fillable = [
		'creator_id',
		'name',
		'description',
		'start_date',
		'end_date',
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'creator_id');
	}

	public function campaign_finances()
	{
		return $this->hasMany(CampaignFinance::class);
	}

	public function donations()
	{
		return $this->hasMany(Donation::class, 'during_campaign_id');
	}

	public function donations_cashes()
	{
		return $this->hasMany(DonationsCash::class);
	}

	public function events()
	{
		return $this->hasMany(Event::class);
	}

	public function staff_assignments()
	{
		return $this->hasMany(StaffAssignment::class, 'Campaign_id');
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class, 'related_campaign_id');
	}
}
