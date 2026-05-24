<?php

namespace App\Services\Admin;

use App\Models\Blog;
use Illuminate\Support\Facades\File;

class BlogService
{
    public function store($data, $image)
    {
        if ($image) {
            $data['image'] = $this->uploadFile($image);
        }
        return Blog::create($data);
    }

    public function update($id, $data, $image)
    {
        $blog = Blog::findOrFail($id);
        if ($image) {
            // Xóa ảnh cũ nếu tồn tại
            if ($blog->image && File::exists(public_path($blog->image))) {
                File::delete(public_path($blog->image));
            }
            $data['image'] = $this->uploadFile($image);
        }
        return $blog->update($data);
    }

    public function delete($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image && File::exists(public_path($blog->image))) {
            File::delete(public_path($blog->image));
        }
        return $blog->delete();
    }

    private function uploadFile($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        // Lưu vào thư mục public/uploads/blogs
        $file->move(public_path('uploads/blogs'), $filename);
        return 'uploads/blogs/' . $filename;
    }
}
