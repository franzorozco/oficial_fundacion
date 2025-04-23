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
 * Class FinancialAccount
 * 
 * @property int $id
 * @property string $name
 * @property string $type
 * @property float|null $balance
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class FinancialAccount extends Model
{
	use SoftDeletes;
	protected $table = 'financial_accounts';

	protected $casts = [
		'balance' => 'float'
	];

	protected $fillable = [
		'name',
		'type',
		'balance',
		'description'
	];

	public function transactions()
	{
		return $this->hasMany(Transaction::class, 'account_id');
	}
}
