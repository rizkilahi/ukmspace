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
        // Eager load UKM relationship to prevent N+1 queries
        $events = Event::with('ukm:id,name,logo')
            ->latest()
            ->take(4)
            ->get();

        $ukms = UKM::where('verification_status', 'active')
            ->take(4)
            ->get();

        return view('home', compact('events', 'ukms'));
    }
}
