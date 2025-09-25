<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;
use App\Models\CampaignFinance;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CampaignFinance
 * 
 * @property int $id
 * @property int $campaign_id
 * @property int $manager_id
 * @property float|null $income
 * @property float|null $expenses
 * @property float|null $net_balance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Campaign $campaign
 *
 * @package App\Models
 */
class CampaignFinance extends Model
{
	use SoftDeletes;
	protected $table = 'campaign_finances';

	protected $casts = [
		'campaign_id' => 'int',
		'manager_id' => 'int',
		'income' => 'float',
		'expenses' => 'float',
		'net_balance' => 'float'
	];

	protected $fillable = [
    'campaign_id', 'manager_id', 'financial_account_id',
    'income', 'expenses', 'net_balance'
];

	public function user()
	{
		return $this->belongsTo(User::class, 'manager_id');
	}

	public function campaign()
	{
		return $this->belongsTo(Campaign::class);
	}

	// App\Models\CampaignFinance.php




}
