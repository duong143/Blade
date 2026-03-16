<?php

namespace App\Http\Controllers;

use App\Models\Combo;

class ComboController extends Controller
{
    public function index()
    {
        $combos = Combo::where('status', 1)
            ->latest()
            ->paginate(12);

        return view('combo.index', compact('combos'));
    }
}
