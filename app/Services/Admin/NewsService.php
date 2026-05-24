<?php

namespace App\Services\Admin;

use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NewsService
{
    public function getPaginatedNews(Request $request)
    {
        $query = News::with('firstImage');

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $perPage = (int) $request->get('per_page', 5);

        return [
            'news' => $query
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->appends($request->query()),
            'perPage' => $perPage,
        ];
    }

    public function createNews(array $data, Request $request): News
    {
        $firstImagePath = null;

        if ($request->hasFile('images')) {
            $firstImagePath = $request->file('images')[0]->store('news', 'public');
        }

        $news = News::create([
            'title' => $data['title'],
            'image' => $firstImagePath,
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if ($index === 0) {
                    $imagePath = $firstImagePath;
                } else {
                    $imagePath = $image->store('news', 'public');
                }

                $news->images()->create([
                    'image' => $imagePath,
                ]);
            }
        }

        return $news;
    }

    public function updateNews(News $news, array $data, Request $request): News
    {
        $updateData = [
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'] ?? null,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('images') && empty($news->image)) {
            $updateData['image'] = $request->file('images')[0]->store('news', 'public');
        }

        $news->update($updateData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if (empty($news->image) && $index === 0 && !empty($updateData['image'])) {
                    $imagePath = $updateData['image'];
                } else {
                    $imagePath = $image->store('news', 'public');
                }

                $news->images()->create([
                    'image' => $imagePath,
                ]);
            }
        }

        return $news;
    }

    public function deleteNews(News $news): void
    {
        $news->load('images');

        foreach ($news->images as $img) {
            if ($img->image && Storage::disk('public')->exists($img->image)) {
                Storage::disk('public')->delete($img->image);
            }
        }

        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->images()->delete();
        $news->delete();
    }

    public function deleteNewsImage(NewsImage $image): array
    {
        try {
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }

            $news = $image->news;
            $deletedImagePath = $image->image;

            $image->delete();

            if ($news && $news->image === $deletedImagePath) {
                $nextImage = $news->images()->latest('id')->first();
                $news->image = $nextImage?->image;
                $news->save();
            }

            return [
                'success' => true,
                'message' => 'Xóa ảnh thành công',
            ];
        } catch (\Throwable $e) {
            Log::error('DELETE_NEWS_IMAGE_FAILED', [
                'image_id' => $image->id ?? null,
                'path' => $image->image ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Xóa ảnh thất bại: ' . $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}
