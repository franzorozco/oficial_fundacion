<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
		'transaction_date' => 'datetime',
		'created_by' => 'int'
	];

	protected $fillable = [
		'account_id',
		'type',
		'amount',
		'description',
		'related_campaign_id',
		'related_payment_id',
		'transaction_date',
		'created_by'
	];

	public function financial_account()
	{
		return $this->belongsTo(FinancialAccount::class, 'account_id');
	}

	public function campaign()
	{
		return $this->belongsTo(Campaign::class, 'related_campaign_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}
}
