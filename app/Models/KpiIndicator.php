<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KpiIndicator
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class KpiIndicator extends Model
{
	use SoftDeletes;
	protected $table = 'kpi_indicators';

	protected $casts = [
		'value' => 'float'
	];

	protected $fillable = [
		'name',
		'description',
		'value'
	];
}
