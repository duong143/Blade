<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComboDepartureSale;
use Illuminate\Http\Request;

class ComboDepartureSaleController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'departure_id' => ['required', 'integer', 'exists:combo_departures,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'sale_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'sale_label' => ['nullable', 'string', 'max:100'],
        ]);

        ComboDepartureSale::updateOrCreate(
            [
                'departure_id' => $data['departure_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ],
            [
                'sale_percent' => $data['sale_percent'],
                'sale_label' => $data['sale_label'] ?? null,
            ]
        );

        return redirect()->back()->with('success', 'Cập nhật sale theo khoảng ngày thành công.');
    }

    public function destroy(ComboDepartureSale $combo_departure_sale)
    {
        $combo_departure_sale->delete();

        return redirect()->back()->with('success', 'Xóa sale thành công.');
    }
}
