<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Http\Requests\Admin\UpdateNewsRequest;
use App\Models\News;
use App\Models\NewsImage;
use App\Services\Admin\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(
        protected NewsService $newsService
    ) {}

    public function index(Request $request)
    {
        $data = $this->newsService->getPaginatedNews($request);

        return view('admin.news.index', $data);
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(StoreNewsRequest $request)
    {
        $this->newsService->createNews($request->validated(), $request);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Thêm tin tức thành công');
    }

    public function edit(News $news)
    {
        $news->load('images');

        return view('admin.news.edit', compact('news'));
    }

    public function update(UpdateNewsRequest $request, News $news)
    {
        $this->newsService->updateNews($news, $request->validated(), $request);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Cập nhật tin tức thành công');
    }

    public function destroy(News $news)
    {
        $this->newsService->deleteNews($news);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Đã xoá tin tức');
    }

    public function deleteImage(NewsImage $image)
    {
        $result = $this->newsService->deleteNewsImage($image);

        if (!($result['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Xóa ảnh thất bại',
            ], $result['status'] ?? 500);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'] ?? 'Xóa ảnh thành công',
        ]);
    }
}
