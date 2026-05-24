<?php

namespace App\Services\Admin;

use App\Models\Attraction;
use Illuminate\Support\Facades\File;

class AttractionService
{
    public function store($data, $image)
    {
        if ($image) {
            $data['image'] = $this->uploadFile($image);
        }
        return Attraction::create($data);
    }

    public function update($id, $data, $image)
    {
        $attraction = Attraction::findOrFail($id);
        if ($image) {
            // Xóa ảnh cũ nếu có
            if ($attraction->image && File::exists(public_path($attraction->image))) {
                File::delete(public_path($attraction->image));
            }
            $data['image'] = $this->uploadFile($image);
        }
        return $attraction->update($data);
    }

    public function delete($id)
    {
        $attraction = Attraction::findOrFail($id);
        if ($attraction->image && File::exists(public_path($attraction->image))) {
            File::delete(public_path($attraction->image));
        }
        return $attraction->delete();
    }

    private function uploadFile($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/attractions'), $filename);
        return 'uploads/attractions/' . $filename;
    }
}