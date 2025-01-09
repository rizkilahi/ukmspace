<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UKM extends Model
{
    use HasFactory;
    protected $table = 'ukms';
    protected $fillable = [
        'name',
        'description',
        'email',
        'password',
        'logo',
        'verification_status',
    ];

    // Relasi Many-to-Many ke User melalui Members
    public function users()
    {
        return $this->belongsToMany(User::class, 'members', 'ukm_id', 'user_id');
    }

    // Relasi One-to-Many ke Event
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
