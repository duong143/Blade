<?php

namespace App\Services\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerService
{
    public function getPaginatedBanners(Request $request)
    {
        $query = Banner::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $perPage = (int) $request->get('per_page', 10);

        return [
            'banners' => $query
                ->orderBy('type')
                ->orderBy('position')
                ->paginate($perPage)
                ->appends($request->query()),
            'perPage' => $perPage,
        ];
    }

    public function createBanner(array $data, Request $request): Banner
    {
        $imagePath = $request->file('image')->store('banners', 'public');

        return Banner::create([
            'type' => $data['type'],
            'title' => $data['title'] ?? null,
            'subtitle' => $data['subtitle'] ?? null,
            'image' => $imagePath,
            'link' => $data['link'] ?? null,
            'position' => $data['position'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
    }

    public function updateBanner(Banner $banner, array $data, Request $request): Banner
    {
        $updateData = [
            'title' => $data['title'] ?? null,
            'subtitle' => $data['subtitle'] ?? null,
            'link' => $data['link'] ?? null,
            'position' => $data['position'] ?? 0,
            'type' => $data['type'],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $this->deleteSingleFile($banner->image);
            $updateData['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($updateData);

        return $banner;
    }

    public function deleteBanner(Banner $banner): void
    {
        $this->deleteSingleFile($banner->image);
        $banner->delete();
    }

    protected function deleteSingleFile(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $fullPath = storage_path('app/public/' . ltrim($relativePath, '/'));

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}