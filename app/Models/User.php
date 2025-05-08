<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\traits\HasRoles;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $address
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|AttachedFile[] $attached_files
 * @property Collection|CampaignFinance[] $campaign_finances
 * @property Collection|Campaign[] $campaigns
 * @property Collection|DonationRequest[] $donation_requests
 * @property Collection|Donation[] $donations
 * @property Collection|DonationsCash[] $donations_cashes
 * @property Collection|EventParticipant[] $event_participants
 * @property Collection|Event[] $events
 * @property ModelHasRole|null $model_has_role
 * @property Collection|Notification[] $notifications
 * @property Collection|Profile[] $profiles
 * @property Collection|Recommendation[] $recommendations
 * @property Collection|StaffAssignment[] $staff_assignments
 * @property Collection|SystemLog[] $system_logs
 * @property Collection|TaskAssignment[] $task_assignments
 * @property Collection|Task[] $tasks
 * @property Collection|Transaction[] $transactions
 * @property Collection|VolunteerVerification[] $volunteer_verifications
 *
 * @package App\Models
 */
class User extends Authenticatable 
{
	use SoftDeletes;
	use HasRoles;
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'phone',
		'address',
		'email_verified_at',
		'password',
		'remember_token'
	];

	public function attached_files()
	{
		return $this->hasMany(AttachedFile::class);
	}

	public function campaign_finances()
	{
		return $this->hasMany(CampaignFinance::class, 'manager_id');
	}

	public function campaigns()
	{
		return $this->hasMany(Campaign::class, 'creator_id');
	}

	public function donation_requests()
	{
		return $this->hasMany(DonationRequest::class, 'user_in_charge_id');
	}

	public function donations()
	{
		return $this->hasMany(Donation::class, 'received_by_id');
	}

	public function donations_cashes()
	{
		return $this->hasMany(DonationsCash::class, 'donor_id');
	}

	public function event_participants()
	{
		return $this->hasMany(EventParticipant::class);
	}
	
	public function eventParticipants()
	{
		return $this->hasMany(EventParticipant::class);
	}
	
	public function events()
	{ 
		return $this->hasMany(Event::class, 'creator_id');
	}

	public function model_has_role()
	{
		return $this->hasOne(ModelHasRole::class, 'model_id');
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class);
	}

	public function profiles()
	{
		return $this->hasMany(Profile::class);
	}

	public function profile()
	{
		return $this->hasOne(Profile::class);
	}

	public function recommendations()
	{
		return $this->hasMany(Recommendation::class);
	}

	public function staff_assignments()
	{
		return $this->hasMany(StaffAssignment::class, 'assigned_by_id');
	}

	public function system_logs()
	{
		return $this->hasMany(SystemLog::class);
	}

	public function task_assignments()
	{
		return $this->hasMany(TaskAssignment::class);
	}

	public function tasks()
	{
		return $this->hasMany(Task::class, 'creator_id');
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class, 'created_by');
	}

	public function volunteer_verifications()
	{
		return $this->hasMany(VolunteerVerification::class, 'user_resp_id');
	}
}
