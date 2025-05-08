<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Donation
 * 
 * @property int $id
 * @property int|null $external_donor_id
 * @property int|null $user_id
 * @property int $received_by_id
 * @property int $status_id
 * @property int|null $during_campaign_id
 * @property Carbon $donation_date
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User|null $user
 * @property User|null $receivedBy
 * @property ExternalDonor|null $externalDonor
 * @property DonationStatus|null $status
 * @property Campaign|null $campaign
 * @property Collection|DonationItem[] $donationItems
 * @property Collection|DonationRequest[] $donationRequests
 *
 * @package App\Models
 */
class Donation extends Model
{
    use SoftDeletes;

    protected $table = 'donations';

    protected $casts = [
        'external_donor_id' => 'integer',
        'user_id' => 'integer',
        'received_by_id' => 'integer',
        'status_id' => 'integer',
        'during_campaign_id' => 'integer',
        'donation_date' => 'datetime',
    ];

    protected $fillable = [
        'external_donor_id',
        'user_id',
        'received_by_id',
        'status_id',
        'during_campaign_id',
        'donation_date',
        'notes',
    ];

    /** 
     * Relación con el donante interno (usuario registrado).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** 
     * Relación con el receptor (usuario que recibe la donación).
     */
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by_id');
    }

    /** 
     * Relación con el donante externo.
     */
    public function externalDonor()
    {
        return $this->belongsTo(ExternalDonor::class, 'external_donor_id');
    }

    /** 
     * Relación con el estado de la donación.
     */
    public function status()
    {
        return $this->belongsTo(DonationStatus::class, 'status_id');
    }

    /** 
     * Relación con la campaña durante la cual se hizo la donación.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'during_campaign_id');
    }

    /**
     * Relación con los ítems de la donación (donation_items).
     */
    public function items()
    {
        return $this->hasMany(DonationItem::class);
    }

    /**
     * (Opcional) Relación con las solicitudes de donación si existieran.
     */
    public function donationRequests()
    {
        return $this->hasMany(DonationRequest::class);
    }
}
