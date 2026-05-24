<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFlightRequest;
use App\Http\Requests\Admin\UpdateFlightRequest;
use App\Services\Admin\FlightService;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function __construct(
        protected FlightService $flightService
    ) {}

    public function index(Request $request)
    {
        $flights = $this->flightService->getPaginatedFlights($request);
        $formData = $this->flightService->getFormData();

        return view('admin.flights.index', array_merge(
            compact('flights'),
            $formData
        ));
    }

    public function create()
    {
        $formData = $this->flightService->getFormData();

        return view('admin.flights.create', $formData);
    }

    public function store(StoreFlightRequest $request)
    {
        $this->flightService->createFlight($request->validated());

        return redirect()
            ->route('admin.flights.index')
            ->with('success', 'Tạo chuyến bay thành công');
    }

    public function edit($id)
    {
        $flight = $this->flightService->getFlightForEdit((int) $id);
        $formData = $this->flightService->getFormData();

        return view('admin.flights.edit', array_merge(
            compact('flight'),
            $formData
        ));
    }

    public function update(UpdateFlightRequest $request, $id)
    {
        $flight = $this->flightService->getFlightForEdit((int) $id);

        $this->flightService->updateFlight($flight, $request->validated());

        return redirect()
            ->route('admin.flights.index')
            ->with('success', 'Cập nhật chuyến bay thành công');
    }

    public function destroy($id)
    {
        $flight = $this->flightService->getFlightForEdit((int) $id);

        $this->flightService->deleteFlight($flight);

        return redirect()
            ->route('admin.flights.index')
            ->with('success', 'Xóa chuyến bay thành công');
    }

    public function toggleStatus($id)
    {
        $flight = $this->flightService->getFlightForEdit((int) $id);

        $this->flightService->toggleStatus($flight);

        return back()->with('success', 'Cập nhật trạng thái chuyến bay thành công');
    }
}
