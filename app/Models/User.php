<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role', // Cambiado de role_id a role
        'setup_completed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'integer', // Aseguramos que role sea siempre un entero
        ];
    }

    /**
     * Constantes para los roles
     */
    const ROLE_FREE = 1;
    const ROLE_BASIC = 2;
    const ROLE_PREMIUM = 3;
    const ROLE_ADMIN = 9;

    /**
     * Valor por defecto para role si no se especifica
     */
    protected $attributes = [
        'role' => self::ROLE_FREE, // Por defecto, todos los usuarios son 'free'
    ];

    /**
     * Verifica si el usuario es admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return (int)$this->role === self::ROLE_ADMIN;
    }

    /**
     * Verifica si el usuario tiene un plan premium.
     *
     * @return bool
     */
    public function isPremium()
    {
        return (int)$this->role === self::ROLE_PREMIUM;
    }

    /**
     * Verifica si el usuario tiene un plan básico.
     *
     * @return bool
     */
    public function isBasic()
    {
        return (int)$this->role === self::ROLE_BASIC;
    }

    /**
     * Verifica si el usuario está en plan gratuito.
     *
     * @return bool
     */
    public function isFree()
    {
        return (int)$this->role === self::ROLE_FREE;
    }

    /**
     * Verifica si el usuario tiene al menos el nivel de rol especificado.
     *
     * @param int $minimumRole
     * @return bool
     */
    public function hasRoleLevel($minimumRole)
    {
        // El admin siempre tiene acceso a todo
        if ((int)$this->role === self::ROLE_ADMIN) {
            return true;
        }
        
        return (int)$this->role >= $minimumRole;
    }

    /**
     * Asigna un rol al usuario.
     *
     * @param int $role
     * @return void
     */
    public function assignRole($role)
    {
        $this->role = $role;
        $this->save();
    }
}