<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Http\Requests\Admin\StoreDestinationRequest;
use App\Http\Requests\Admin\UpdateDestinationRequest;
use App\Services\Admin\DestinationService;

class DestinationController extends Controller
{
    protected $destinationService;

    public function __construct(DestinationService $destinationService)
    {
        $this->destinationService = $destinationService;
    }

    public function index(\Illuminate\Http\Request $request)
    {
        
        $destinations = $this->destinationService->getPaginatedDestinations($request->all());

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(StoreDestinationRequest $request)
    {
        $this->destinationService->storeDestination(
            $request->except('image'),
            $request->file('image')
        );

        return redirect()->route('admin.destinations.index')->with('success', 'Thêm địa điểm thành công!');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(UpdateDestinationRequest $request, Destination $destination)
    {
        $this->destinationService->updateDestination(
            $destination,
            $request->except('image'),
            $request->file('image')
        );

        return redirect()->route('admin.destinations.index')->with('success', 'Cập nhật địa điểm thành công!');
    }

    public function destroy(Destination $destination)
    {
        $this->destinationService->deleteDestination($destination);

        return redirect()->route('admin.destinations.index')->with('success', 'Đã xóa địa điểm!');
    }
}
