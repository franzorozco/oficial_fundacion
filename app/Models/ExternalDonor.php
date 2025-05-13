<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;  // Asegúrate de importar este trait
/**
 * Class ExternalDonor
 * 
 * @property int $id
 * @property string $names
 * @property string|null $paternal_surname
 * @property string|null $maternal_surname
 * @property string $email
 * @property string|null $phone
 * @property string|null $address
 * 
 * @property Collection|Donation[] $donations
 * @property Collection|DonationsCash[] $donations_cashes
 *
 * @package App\Models
 */
	class ExternalDonor extends Model
	{
		use SoftDeletes;  // Añade SoftDeletes aquí
		protected $table = 'external_donor';
		public $timestamps = false;

		protected $fillable = [
			'names',
			'paternal_surname',
			'maternal_surname',
			'email',
			'phone',
			'address'
		];

		public function donations()
		{
			return $this->hasMany(Donation::class);
		}

		public function donations_cashes()
		{
			return $this->hasMany(DonationsCash::class);
		}
	}
