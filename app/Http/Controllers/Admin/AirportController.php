<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAirportRequest;
use App\Http\Requests\Admin\UpdateAirportRequest;
use App\Services\Admin\AirportService;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function __construct(
        protected AirportService $airportService
    ) {}

    public function index(Request $request)
    {
        $airports = $this->airportService->getPaginatedAirports($request);

        return view('admin.airports.index', compact('airports'));
    }

    public function create()
    {
        return view('admin.airports.create');
    }

    public function store(StoreAirportRequest $request)
    {
        $this->airportService->createAirport($request->validated());

        return redirect()
            ->route('admin.airports.index')
            ->with('success', 'Tạo sân bay thành công');
    }

    public function edit($id)
    {
        $airport = $this->airportService->getAirportById((int) $id);

        return view('admin.airports.edit', compact('airport'));
    }

    public function update(UpdateAirportRequest $request, $id)
    {
        $airport = $this->airportService->getAirportById((int) $id);

        $this->airportService->updateAirport($airport, $request->validated());

        return redirect()
            ->route('admin.airports.index')
            ->with('success', 'Cập nhật sân bay thành công');
    }

    public function destroy($id)
    {
        $airport = $this->airportService->getAirportById((int) $id);

        $this->airportService->deleteAirport($airport);

        return redirect()
            ->route('admin.airports.index')
            ->with('success', 'Xóa sân bay thành công');
    }

    public function toggleStatus($id)
    {
        $airport = $this->airportService->getAirportById((int) $id);

        $this->airportService->toggleStatus($airport);

        return back()->with('success', 'Cập nhật trạng thái sân bay thành công');
    }
}
