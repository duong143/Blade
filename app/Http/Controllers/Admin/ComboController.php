<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ComboImage;
use Illuminate\Support\Facades\DB;

class ComboController extends Controller
{
    public function index()
    {
        $combos = Combo::latest()->paginate(10);
        return view('admin.combos.index', compact('combos'));
    }

    public function create()
    {
        return view('admin.combos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['nullable', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'content_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'from_location' => ['nullable', 'string', 'max:255'],
            'to_location' => ['nullable', 'string', 'max:255'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:60'],
            'duration_nights' => ['required', 'integer', 'min:0', 'max:60'],
            'preorder_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'short_desc' => ['nullable', 'string', 'max:500'],
            'hotel_amenities' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'itinerary_detail' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('combos', 'public');
        }
        if ($request->hasFile('content_image')) {
            $data['content_image'] = $request->file('content_image')->store('combos/content', 'public');
        }

        if (empty($data['code'])) {
            $data['code'] = 'CB' . now()->format('YmdHis');
        }

        DB::transaction(function () use ($request, $data) {
            $combo = Combo::create($data);

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $imageFile) {
                    $path = $imageFile->store('combos/gallery', 'public');

                    ComboImage::create([
                        'combo_id' => $combo->id,
                        'image_path' => $path,
                        'is_cover' => false,
                        'sort_order' => $index,
                    ]);
                }
            }
        });

        return redirect()->route('admin.combos.index')->with('success', 'Tạo combo thành công');
    }

    public function edit($id)
    {
        $combo = Combo::with('images')->findOrFail($id);
        return view('admin.combos.edit', compact('combo'));
    }

    public function update(Request $request, $id)
    {
        $combo = Combo::with('images')->findOrFail($id);

        $data = $request->validate([
            'code' => ['nullable', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'content_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'from_location' => ['nullable', 'string', 'max:255'],
            'to_location' => ['nullable', 'string', 'max:255'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:60'],
            'duration_nights' => ['required', 'integer', 'min:0', 'max:60'],
            'preorder_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'short_desc' => ['nullable', 'string', 'max:500'],
            'hotel_amenities' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'itinerary_detail' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);
        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('image')) {
            if (!empty($combo->image)) {
                $oldPath = storage_path('app/public/' . ltrim($combo->image, '/'));
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $data['image'] = $request->file('image')->store('combos', 'public');
        }
        if ($request->hasFile('content_image')) {
            if (!empty($combo->content_image)) {
                $oldPath = storage_path('app/public/' . ltrim($combo->content_image, '/'));
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $data['content_image'] = $request->file('content_image')->store('combos/content', 'public');
        }

        if (empty($data['code'])) {
            $data['code'] = $combo->code;
        }

        DB::transaction(function () use ($request, $combo, $data) {
            $combo->update($data);

            if ($request->hasFile('gallery_images')) {
                $currentMaxSort = (int) ($combo->images()->max('sort_order') ?? -1);

                foreach ($request->file('gallery_images') as $index => $imageFile) {
                    $path = $imageFile->store('combos/gallery', 'public');

                    ComboImage::create([
                        'combo_id' => $combo->id,
                        'image_path' => $path,
                        'is_cover' => false,
                        'sort_order' => $currentMaxSort + $index + 1,
                    ]);
                }
            }
        });

        return redirect()->route('admin.combos.index')->with('success', 'Cập nhật combo thành công');
    }

    public function destroy($id)
    {
        $combo = Combo::with('images')->findOrFail($id);

        if (!empty($combo->image)) {
            $oldPath = storage_path('app/public/' . ltrim($combo->image, '/'));
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        foreach ($combo->images as $img) {
            if (!empty($img->image_path)) {
                $galleryPath = storage_path('app/public/' . ltrim($img->image_path, '/'));
                if (is_file($galleryPath)) {
                    @unlink($galleryPath);
                }
            }
        }

        $combo->delete();

        return redirect()->route('admin.combos.index')->with('success', 'Xóa combo thành công');
    }

    public function destroyImage($id)
    {
        $image = ComboImage::findOrFail($id);

        if (!empty($image->image_path)) {
            $oldPath = storage_path('app/public/' . ltrim($image->image_path, '/'));
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $image->delete();

        return back()->with('success', 'Xóa ảnh banner thành công');
    }
}
