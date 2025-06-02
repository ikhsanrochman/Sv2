<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role_id',
        'no_sib',
        'berlaku',
        'is_active',
        'npr',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function ketersediaanSdm()
    {
        return $this->belongsToMany(KetersediaanSdm::class, 'ketersediaan_sdm_users');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function jenisPekerja()
    {
        return $this->belongsToMany(JenisPekerja::class, 'jenis_pekerja_user');
    }

    /**
     * Get the pemantauan dosis tld records for the user.
     */
    public function pemantauanDosisTld(): HasMany
    {
        return $this->hasMany(PemantauanDosisTld::class);
    }
}

