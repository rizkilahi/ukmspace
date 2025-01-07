<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Data yang ditampilkan di halaman utama
        $events = \App\Models\Event::latest()->take(5)->get(); // Event terbaru
        $ukms = \App\Models\UKM::where('verification_status', 'active')->take(5)->get(); // UKM aktif
        // $categories = \App\Models\Category::all(); // Semua kategori
        // $venues = \App\Models\Venue::all(); // Semua venue
        // $testimonials = \App\Models\Testimonial::latest()->take(3)->get(); // Testimonial terbaru

        // Mengirim data ke view menggunakan array
        return view('home', [
            'events' => $events,
            'ukms' => $ukms,
            // 'categories' => $categories,
            // 'venues' => $venues,
            // 'testimonials' => $testimonials,
        ]);
    }
}
