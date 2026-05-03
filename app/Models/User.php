<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    /**
     * Check if the user has a specific role or roles.
     * Usage: auth()->user()->hasRole('Admin', 'Receptionist')
     */
    public function hasRole(...$roles)
    {
        return in_array($this->role, $roles);
    }

    public function appointments()
    {
    // We specify 'staff_id' as the foreign key in the appointments table
    return $this->hasMany(Appointment::class, 'staff_id');
    }

    
}
