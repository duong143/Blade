<?php

namespace App\Http\Controllers;

use App\Models\Banner;
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
}
