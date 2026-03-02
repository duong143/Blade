<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use App\Models\NewsImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('firstImage');

        // 🔍 Filter theo tiêu đề
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // 🔘 Filter theo hiển thị
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // 🔢 Số dòng / trang (mặc định 5 cho tin tức)
        $perPage = $request->get('per_page', 5);

        $news = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.news.index', compact('news', 'perPage'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'images.*' => 'image'
        ]);

        // 1. Tạo tin tức
        $news = News::create([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'is_active' => $request->has('is_active'),
        ]);

        // 2. Lưu nhiều ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $imagePath = $image->store('news', 'public');

                $news->images()->create([
                    'image' => $imagePath
                ]);
            }
        }

        return redirect()->route('admin.news.index')
            ->with('success', 'Thêm tin tức thành công');
    }


    public function edit(News $news)
    {
        $news->load('images');
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required',
            'images.*' => 'image'
        ]);

        // 1. Cập nhật nội dung tin
        $news->update([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'is_active' => $request->has('is_active'),
        ]);

        // 2. Thêm ảnh mới (nếu có)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $imagePath = $image->store('news', 'public');


                $news->images()->create([
                    'image' => $imagePath
                ]);
            }
        }

        return redirect()->route('admin.news.index')
            ->with('success', 'Cập nhật tin tức thành công');
    }


    public function destroy(News $news)
    {
        $news->load('images');

        foreach ($news->images as $img) {
            if ($img->image && Storage::disk('public')->exists($img->image)) {
                Storage::disk('public')->delete($img->image);
            }
        }

        $news->images()->delete();
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Đã xoá tin tức');
    }

    public function deleteImage(NewsImage $image)
    {
        try {
            // xoá file vật lý
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }

            // xoá record DB
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa ảnh thành công',
            ]);
        } catch (\Throwable $e) {
            Log::error('DELETE_NEWS_IMAGE_FAILED', [
                'image_id' => $image->id ?? null,
                'path' => $image->image ?? null,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Xóa ảnh thất bại: ' . $e->getMessage(),
            ], 500);
        }
    }
}
