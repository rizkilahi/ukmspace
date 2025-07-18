<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\UKM;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $popularEvents = Event::latest()->take(4)->get(); // Ambil 4 event terbaru
        $popularUKMs = UKM::where('verification_status', 'active')->take(4)->get(); // Ambil 4 UKM aktif

        // Also pass variables with the names expected by the view
        $events = $popularEvents;
        $ukms = $popularUKMs;

        return view('home', compact('popularEvents', 'popularUKMs', 'events', 'ukms'));
    }
}
