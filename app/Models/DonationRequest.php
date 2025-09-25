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
 * @property int $applicant_user_id  // Corregido el nombre del campo
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
        'applicant_user_id' => 'int',  // Cambiado el nombre del campo
        'user_in_charge_id' => 'int',
        'donation_id' => 'int',
        'request_date' => 'datetime',
    ];

    protected $fillable = [
        'applicant_user__id',
        'user_in_charge_id',
        'donation_id',
        'request_date',
        'notes',
        'observacions',
        'state',
        'referencia', // incluir este campo
    ];


    /**
     * Relación con el usuario solicitante (applicant_user_id)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'applicant_user_id'); // Cambiado el campo de relación
    }

    /**
     * Relación con la donación asociada
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }


    // DonationRequest.php
    public function description()
    {
        return $this->hasOne(DonationRequestDescription::class);
    }



    /**
     * Relación con las descripciones de la solicitud de donación
     */
    public function DonationRequestDescription()
    {
        return $this->hasMany(DonationRequestDescription::class);
    }

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant_user__id');
    }

    public function userInCharge()
    {
        return $this->belongsTo(User::class, 'user_in_charge_id'); // Agregado el campo de encargado
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class, 'donation_request_id');
    }

}
