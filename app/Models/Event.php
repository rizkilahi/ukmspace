<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'ukm_id',
        'title',
        'description',
        'event_date',
        'location',
    ];

    // Relasi Many-to-Many ke User melalui EventRegistrations
    public function users()
    {
        return $this->belongsToMany(User::class, 'event_registrations', 'event_id', 'user_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Relasi ke UKM (Many-to-One)
    public function ukm()
    {
        return $this->belongsTo(UKM::class);
    }
}
