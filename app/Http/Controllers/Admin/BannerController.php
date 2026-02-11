<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::query();

        // 🔍 Filter theo tiêu đề
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // 🔽 Filter theo loại banner
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 🔘 Filter theo trạng thái hiển thị
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        $perPage = $request->get('per_page', 10);

        $banners = $query
            ->orderBy('type')
            ->orderBy('position')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.banners.index', compact('banners', 'perPage'));
    }


    // Form tạo banner
    public function create()
    {
        return view('admin.banners.create');
    }

    // Lưu banner
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'type' => $request->type,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $imagePath,
            'link' => $request->link,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Thêm banner thành công');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->only(['title', 'subtitle', 'link', 'position', 'type']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Cập nhật banner thành công');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Đã xoá banner');
    }
}
