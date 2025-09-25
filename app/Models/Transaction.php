<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FinancialAccount;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\EventLocation;
use App\Models\User;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property int $account_id
 * @property string $type
 * @property float $amount
 * @property string|null $description
 * @property int|null $related_campaign_id
 * @property int|null $related_payment_id
 * @property Carbon $transaction_date
 * @property int|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property FinancialAccount $financial_account
 * @property Campaign|null $campaign
 * @property User|null $user
 *
 * @package App\Models
 */
class Transaction extends Model
{
	use SoftDeletes;
	protected $table = 'transactions';

	protected $casts = [
		'account_id' => 'int',
		'amount' => 'float',
		'related_campaign_id' => 'int',
		'related_payment_id' => 'int',
		'transaction_date' => 'datetime:Y-m-d',
		'transaction_time' => 'datetime:H:i:s',

		'created_by' => 'int'
	];

	protected $fillable = [
		'account_id',
		'type',
		'amount',
		'description',
		'related_campaign_id',
		'related_event_id',
		'related_event_location_id',
		'transaction_date',
		'transaction_time',
		'created_by'
	];

	public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:financial_accounts,id'],
            'type' => ['required', 'in:ingreso,gasto'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'related_campaign_id' => ['nullable', 'exists:campaigns,id'],
            'related_event_id' => ['nullable', 'exists:events,id'],
            'related_event_location_id' => ['nullable', 'exists:event_locations,id'],
            'transaction_date' => ['required', 'date'],
            'transaction_time' => ['required', 'date_format:H:i'],
        ];
    }
	
	public function financial_account()
	{
		return $this->belongsTo(FinancialAccount::class, 'account_id');
	}

	public function campaign()
	{
		return $this->belongsTo(Campaign::class, 'related_campaign_id');
	}

	public function event()
	{
		return $this->belongsTo(Event::class, 'related_event_id');
	}
	public function event_location()
	{
		return $this->belongsTo(EventLocation::class, 'related_event_location_id');
	}
	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}
}
