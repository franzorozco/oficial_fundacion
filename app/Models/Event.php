<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event
 *
 * @property $id
 * @property $campaign_id
 * @property $creator_id
 * @property $name
 * @property $description
 * @property $event_date
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property User $user
 * @property Campaign $campaign
 * @property EventLocation[] $eventLocations
 * @property EventParticipant[] $eventParticipants
 * @property StaffAssignment[] $staffAssignments
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Event extends Model
{
    use SoftDeletes;

    protected $perPage = 20;
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['campaign_id', 'creator_id', 'name', 'description', 'event_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'creator_id', 'id');
        
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(\App\Models\Campaign::class, 'campaign_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventLocations()
    {
        return $this->hasMany(\App\Models\EventLocation::class, 'event_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventParticipants()
    {
        return $this->hasMany(\App\Models\EventParticipant::class, 'event_id', 'id');
        
    }
    // En Event.php
    public function participants() {
        return $this->hasMany(EventParticipant::class);
    }

    public function locations() {
        return $this->hasMany(EventLocation::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staffAssignments()
    {
        return $this->hasMany(\App\Models\StaffAssignment::class, 'id', 'event_id');
    }

    public function eventLocationsTrashed()
    {
        return $this->hasMany(EventLocation::class)->onlyTrashed();
    }


    
}
