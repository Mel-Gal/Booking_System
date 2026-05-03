<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id', 'staff_id', 'service_type', 'appointment_date', 
        'appointment_time', 'status', 'notes', 'created_by',
        'scheduled_at', 'confirmed_at', 'completed_at', 'cancelled_at','no_show_at'
    ];

    protected $casts = [
    'scheduled_at' => 'datetime',
    'confirmed_at' => 'datetime',
    'completed_at' => 'datetime',
    'cancelled_at' => 'datetime',
    'no_show_at' => 'datetime',
    'appointment_date' => 'date',
];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function serviceRecord()
    {
        return $this->hasOne(ServiceRecord::class);
    }

    public function getStatusColorAttribute()
    {
    return match($this->status) {
        'Scheduled' => 'bg-blue-100 text-blue-800',
        'Confirmed' => 'bg-cyan-100 text-cyan-800',
        'Completed' => 'bg-green-100 text-green-800',
        'No Show'   => 'bg-purple-100 text-purple-800',
        'Cancelled' => 'bg-red-100 text-red-800',
        default     => 'bg-gray-100 text-gray-800',
    };
    }
}
