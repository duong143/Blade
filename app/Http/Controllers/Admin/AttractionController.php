<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Destination;
use App\Http\Requests\Admin\StoreAttractionRequest;
use App\Http\Requests\Admin\UpdateAttractionRequest;
use App\Services\Admin\AttractionService;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    protected $service;

    public function __construct(AttractionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = Attraction::with('destination');

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->destination_id) {
            $query->where('destination_id', $request->destination_id);
        }

        $attractions = $query->latest()->paginate(10);
        $destinations = Destination::all();

        return view('admin.attractions.index', compact('attractions', 'destinations'));
    }

    public function create()
    {
        $destinations = Destination::all();
        return view('admin.attractions.create', compact('destinations'));
    }

    public function store(StoreAttractionRequest $request)
    {
        $this->service->store($request->validated(), $request->file('image'));
        return redirect()->route('admin.attractions.index')->with('success', 'Đã thêm điểm check-in mới!');
    }

    public function edit($id)
    {
        $attraction = Attraction::findOrFail($id);
        $destinations = Destination::all();
        return view('admin.attractions.edit', compact('attraction', 'destinations'));
    }

    public function update(UpdateAttractionRequest $request, $id)
    {
        $this->service->update($id, $request->validated(), $request->file('image'));
        return redirect()->route('admin.attractions.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return back()->with('success', 'Đã xóa địa điểm!');
    }
}