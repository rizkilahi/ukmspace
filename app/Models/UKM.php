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
        'logo',
        'address',
        'phone',
        'website',
        'established_date',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'password', // Password should be set explicitly
        'verification_status', // Only admins can change this
    ];

    /**
     * Relasi Many-to-Many ke User melalui tabel pivot Members
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'members', 'ukm_id', 'user_id')
                    ->withTimestamps(); // Menambahkan informasi waktu pada pivot
    }

    /**
     * Relasi One-to-Many ke Event
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'ukm_id'); // Pastikan foreign key sesuai dengan nama kolom
    }

    /**
     * Scope untuk UKM yang diverifikasi
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'active');
    }

    /**
     * Scope untuk pencarian UKM berdasarkan nama
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    /**
     * Mutator untuk hashing password saat menyimpan ke database
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Accessor untuk menampilkan logo dengan path lengkap
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-logo.png');
    }
}
