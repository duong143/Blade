<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComboDeparture;
use App\Models\ComboDeparturePrice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComboDeparturePriceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'departure_id'    => ['required', 'integer', 'exists:combo_departures,id'],
            'passenger_type'  => ['required', Rule::in(['adult', 'child', 'infant'])],
            'base_price'      => ['required', 'integer', 'min:0'],
        ]);

        // 1 departure chỉ nên có 1 giá cho mỗi passenger_type
        ComboDeparturePrice::updateOrCreate(
            [
                'departure_id' => $data['departure_id'],
                'passenger_type' => $data['passenger_type'],
            ],
            [
                'base_price' => $data['base_price'],
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Cập nhật giá thành công.');
    }

    public function destroy(ComboDeparturePrice $combo_departure_price)
    {
        $combo_departure_price->delete();

        return redirect()
            ->back()
            ->with('success', 'Xóa giá thành công.');
    }
}
