<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Services\Admin\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $service;

    public function __construct(BlogService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = Blog::query();
        if ($request->keyword) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }
        $blogs = $query->latest()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(StoreBlogRequest $request)
    {
        $this->service->store($request->validated(), $request->file('image'));
        return redirect()->route('admin.blogs.index')->with('success', 'Đã đăng bài viết mới!');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(UpdateBlogRequest $request, $id)
    {
        $this->service->update($id, $request->validated(), $request->file('image'));
        return redirect()->route('admin.blogs.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return back()->with('success', 'Đã xóa bài viết!');
    }
}
