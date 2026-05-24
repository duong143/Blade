<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Http\Requests\Admin\UpdateBannerRequest;
use App\Models\Banner;
use App\Services\Admin\BannerService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(
        protected BannerService $bannerService
    ) {}

    public function index(Request $request)
    {
        $data = $this->bannerService->getPaginatedBanners($request);

        return view('admin.banners.index', $data);
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(StoreBannerRequest $request)
    {
        $this->bannerService->createBanner($request->validated(), $request);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Thêm banner thành công');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $this->bannerService->updateBanner($banner, $request->validated(), $request);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Cập nhật banner thành công');
    }

    public function destroy(Banner $banner)
    {
        $this->bannerService->deleteBanner($banner);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Đã xoá banner');
    }
}
