<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAirlineRequest;
use App\Http\Requests\Admin\UpdateAirlineRequest;
use App\Services\Admin\AirlineService;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    public function __construct(
        protected AirlineService $airlineService
    ) {}

    public function index(Request $request)
    {
        $airlines = $this->airlineService->getPaginatedAirlines($request);

        return view('admin.airlines.index', compact('airlines'));
    }

    public function create()
    {
        return view('admin.airlines.create');
    }

    public function store(StoreAirlineRequest $request)
    {
        $this->airlineService->createAirline($request->validated(), $request);

        return redirect()
            ->route('admin.airlines.index')
            ->with('success', 'Tạo hãng bay thành công');
    }

    public function edit($id)
    {
        $airline = $this->airlineService->getAirlineById((int) $id);

        return view('admin.airlines.edit', compact('airline'));
    }

    public function update(UpdateAirlineRequest $request, $id)
    {
        $airline = $this->airlineService->getAirlineById((int) $id);

        $this->airlineService->updateAirline($airline, $request->validated(), $request);

        return redirect()
            ->route('admin.airlines.index')
            ->with('success', 'Cập nhật hãng bay thành công');
    }

    public function destroy($id)
    {
        $airline = $this->airlineService->getAirlineById((int) $id);

        $this->airlineService->deleteAirline($airline);

        return redirect()
            ->route('admin.airlines.index')
            ->with('success', 'Xóa hãng bay thành công');
    }

    public function toggleStatus($id)
    {
        $airline = $this->airlineService->getAirlineById((int) $id);

        $this->airlineService->toggleStatus($airline);

        return back()->with('success', 'Cập nhật trạng thái hãng bay thành công');
    }
}
