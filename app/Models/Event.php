<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'location',
        'event_date',
        'ukm_id',
    ];

    /**
     * Relasi Many-to-Many ke User melalui EventRegistrations
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'event_registrations', 'event_id', 'user_id')
                    ->withPivot('status') // Menyertakan kolom tambahan di tabel pivot
                    ->withTimestamps();  // Menyertakan timestamp untuk pivot
    }

    /**
     * Relasi Many-to-One ke UKM
     */
    public function ukm()
    {
        return $this->belongsTo(UKM::class, 'ukm_id'); // Pastikan foreign key sesuai dengan nama kolom
    }

    /**
     * Relasi One-to-Many ke EventRegistration
     * Memungkinkan akses langsung ke registrasi tanpa melalui tabel pivot.
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Scope untuk pencarian berdasarkan lokasi
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Scope untuk pencarian berdasarkan tanggal
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('event_date', $date);
    }
}
