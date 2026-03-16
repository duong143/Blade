<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\ComboDeparture;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComboDepartureController extends Controller
{
    public function index(Request $request)
    {
        $comboId = $request->query('combo_id');

        $query = ComboDeparture::with('combo')->orderByDesc('start_date');
        if ($comboId) {
            $query->where('combo_id', $comboId);
        }

        $departures = $query->paginate(20)->withQueryString();
        $combos = Combo::orderByDesc('id')->get(['id', 'title']);

        return view('admin.combo_departures.index', compact('departures', 'combos', 'comboId'));
    }

    public function create(Request $request)
    {
        $comboId = $request->query('combo_id');
        $combos = Combo::orderByDesc('id')->get(['id', 'title']);

        return view('admin.combo_departures.create', compact('combos', 'comboId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'combo_id'   => ['required', 'integer', 'exists:combos,id'],
            'start_date' => [
                'required',
                'date',
                Rule::unique('combo_departures')->where(
                    fn($q) =>
                    $q->where('combo_id', $request->combo_id)
                ),
            ],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
            'capacity'   => ['required', 'integer', 'min:1'],
            'sold'       => ['nullable', 'integer', 'min:0'],
            'status'     => ['nullable', 'boolean'],
        ], [
            'start_date.unique' => 'Ngày khởi hành này đã tồn tại cho combo đã chọn.',
        ]);

        $data['sold'] = $data['sold'] ?? 0;
        $data['status'] = (int)($data['status'] ?? 1);

        if ($data['sold'] > $data['capacity']) {
            return back()->withInput()->with('error', 'Sold không được lớn hơn Capacity.');
        }

        ComboDeparture::create($data);

        return redirect()
            ->route('admin.combo-departures.index', ['combo_id' => $data['combo_id']])
            ->with('success', 'Tạo đợt khởi hành thành công.');
    }

    public function edit(ComboDeparture $combo_departure)
    {
        $combos = Combo::orderByDesc('id')->get(['id', 'title']);

        $combo_departure->load('prices', 'sales');

        return view('admin.combo_departures.edit', [
            'departure' => $combo_departure,
            'combos' => $combos,
        ]);
    }

    public function update(Request $request, ComboDeparture $combo_departure)
    {
        $data = $request->validate([
            'combo_id'   => ['required', 'integer', 'exists:combos,id'],
            'start_date' => [
                'required',
                'date',
                Rule::unique('combo_departures')
                    ->ignore($combo_departure->id)
                    ->where(fn($q) => $q->where('combo_id', $request->combo_id)),
            ],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
            'capacity'   => ['required', 'integer', 'min:1'],
            'sold'       => ['nullable', 'integer', 'min:0'],
            'status'     => ['nullable', 'boolean'],
        ], [
            'start_date.unique' => 'Ngày khởi hành này đã tồn tại cho combo đã chọn.',
        ]);

        $data['sold'] = $data['sold'] ?? 0;
        $data['status'] = (int)($data['status'] ?? 1);

        if ($data['sold'] > $data['capacity']) {
            return back()->withInput()->with('error', 'Sold không được lớn hơn Capacity.');
        }

        $combo_departure->update($data);

        return redirect()
            ->route('admin.combo-departures.index', ['combo_id' => $data['combo_id']])
            ->with('success', 'Cập nhật đợt khởi hành thành công.');
    }

    public function destroy(ComboDeparture $combo_departure)
    {
        $comboId = $combo_departure->combo_id;
        $combo_departure->delete();

        return redirect()
            ->route('admin.combo-departures.index', ['combo_id' => $comboId])
            ->with('success', 'Xóa đợt khởi hành thành công.');
    }
}
