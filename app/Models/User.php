<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi: satu user punya satu profile.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Relasi: company melalui profile (opsional, kalau mau pakai).
     */
    public function company()
    {
        return $this->hasOneThrough(
            Perusahaan::class, // model tujuan
            Profile::class, // model perantara
            'user_id',      // foreign key di profiles
            'id',           // primary key di companies
            'id',           // local key di users
            'company_id'    // foreign key di profiles ke companies
        );
    }

    /**
     * Helper: akses company_id seolah masih ada di users.
     * Berguna kalau masih ada kode lama yang panggil $user->company_id.
     */
    public function getCompanyIdAttribute(): ?int
    {
        return $this->profile->company_id ?? null;
    }

    public function dashboardRoute()
    {
        $map = [
            'superadmin'      => 'dashboard.superadmin',
            'admin'           => 'dashboard.admin',
            'lead-operations' => 'dashboard.lead-operations',
        ];

        foreach ($map as $role => $route) {
            if ($this->hasRole($role)) {
                return route($route);
            }
        }

        return url('/'); // fallback
    }
}