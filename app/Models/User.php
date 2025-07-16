<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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
        'phone',
        'role', // Role user (e.g., user, ukm, admin)
        'ukm_id', // Tambahkan kolom ukm_id untuk relasi belongsTo
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
        ];
    }

    /**
     * Relasi ke UKM untuk User yang memiliki satu UKM (Kolom ukm_id).
     */
    public function ukm()
    {
        return $this->belongsTo(UKM::class, 'ukm_id');
    }

    /**
     * Relasi Many-to-Many ke UKM melalui tabel pivot Members.
     */
    public function ukms()
    {
        return $this->belongsToMany(UKM::class, 'members', 'user_id', 'ukm_id')
                    ->withTimestamps();
    }

    /**
     * Relasi Many-to-Many ke Event melalui tabel pivot EventRegistrations.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_registrations', 'user_id', 'event_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
