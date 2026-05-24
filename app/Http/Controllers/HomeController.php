<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        $mainBanners = Banner::where('type', 'main')
            ->where('is_active', 1)
            ->orderBy('position')
            ->get();

        $smallBanners = Banner::where('type', 'small')
            ->where('is_active', 1)
            ->orderBy('position')
            ->limit(6)
            ->get();

        // 🔧 SỬA DUY NHẤT: load images
        $newsList = News::with('images')
            ->where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('home', compact(
            'mainBanners',
            'smallBanners',
            'newsList'
        ));
    }

    public function destinations(Request $request)
    {
        $currentMonth = date('n');
        $monthFilter = $request->query('month', $currentMonth);

        $destinations = Destination::where('status', 1)
            ->when($monthFilter, function ($query) use ($monthFilter) {
                return $query->whereJsonContains('recommended_months', (string)$monthFilter);
            })
            ->get();

        return view('destinations.index', compact('destinations', 'monthFilter', 'currentMonth'));
    }

    public function showDestination($slug)
    {
        
        $destination = \App\Models\Destination::where('slug', $slug)
            ->where('status', 1)
            ->with('attractions')
            ->firstOrFail();

        return view('destinations.show', compact('destination'));
    }
}
