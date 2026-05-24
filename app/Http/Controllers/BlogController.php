<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Trang danh sách cẩm nang
    public function index()
    {
        $blogs = Blog::latest()->paginate(9); // Lấy mỗi trang 9 bài
        return view('blogs.index', compact('blogs'));
    }

    // Trang chi tiết bài viết
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        
        // Lấy thêm 3 bài viết mới nhất khác bài đang xem để gợi ý "Bài viết liên quan"
        $relatedBlogs = Blog::where('id', '!=', $id)->latest()->take(3)->get();
        
        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }
}