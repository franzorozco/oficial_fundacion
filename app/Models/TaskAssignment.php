<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TaskAssignment
 *
 * @property $id
 * @property $task_id
 * @property $donation_request_id
 * @property $user_id
 * @property $supervisor
 * @property $status
 * @property $assigned_at
 * @property $notes
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property DonationRequest $donationRequest
 * @property User $user
 * @property Task $task
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class TaskAssignment extends Model
{
    use SoftDeletes;

    protected $perPage = 20;

    protected $fillable = [
        'task_id',
        'donation_request_id',
        'user_id',
        'supervisor',
        'status',
        'assigned_at',
        'notes'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function donationRequest()
    {
        return $this->belongsTo(DonationRequest::class, 'donation_request_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supervisorUser()
    {
        return $this->belongsTo(User::class, 'supervisor');
    }
}