<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'address', 'notes'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class);
    }
}
