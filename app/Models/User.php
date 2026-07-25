<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROL_ADMIN = 'admin';
    public const ROL_RECEPCIONISTA = 'recepcionista';
    public const ROL_DOCTOR = 'doctor';
    public const ROL_PACIENTE = 'paciente';

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }

    public function esAdmin(): bool
    {
        return $this->rol === self::ROL_ADMIN;
    }

    public function esRecepcionista(): bool
    {
        return $this->rol === self::ROL_RECEPCIONISTA;
    }

    public function esDoctor(): bool
    {
        return $this->rol === self::ROL_DOCTOR;
    }

    public function esPaciente(): bool
    {
        return $this->rol === self::ROL_PACIENTE;
    }
}
