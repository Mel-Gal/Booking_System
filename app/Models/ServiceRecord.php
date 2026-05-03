<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ServiceRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'appointment_id', 'client_id', 'staff_id', 'description', 'service_date', 'remarks'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class)->withTrashed();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
