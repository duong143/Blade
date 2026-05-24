<?php

namespace App\Services\Admin;

use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class DestinationService
{

    public function getPaginatedDestinations($filters = [], $perPage = 10)
    {
        $query = \App\Models\Destination::query();

        
        if (!empty($filters['keyword'])) {
            $query->where('name', 'like', '%' . $filters['keyword'] . '%');
        }

        
        if (!empty($filters['month'])) {
            $query->whereJsonContains('recommended_months', (string)$filters['month']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function storeDestination(array $data, $imageFile = null)
    {
        $data['slug'] = Str::slug($data['name']);
        $data['status'] = isset($data['status']) ? 1 : 0;

        if ($imageFile) {
            $filename = time() . '_' . $imageFile->getClientOriginalName();

            if (!File::exists(public_path('uploads/destinations'))) {
                File::makeDirectory(public_path('uploads/destinations'), 0755, true);
            }
            $imageFile->move(public_path('uploads/destinations'), $filename);
            $data['image'] = 'uploads/destinations/' . $filename;
        }

        return Destination::create($data);
    }

    public function updateDestination(Destination $destination, array $data, $imageFile = null)
    {
        $data['slug'] = Str::slug($data['name']);
        $data['status'] = isset($data['status']) ? 1 : 0;

        if ($imageFile) {
            $filename = time() . '_' . $imageFile->getClientOriginalName();
            if (!File::exists(public_path('uploads/destinations'))) {
                File::makeDirectory(public_path('uploads/destinations'), 0755, true);
            }
            $imageFile->move(public_path('uploads/destinations'), $filename);
            $data['image'] = 'uploads/destinations/' . $filename;


            if ($destination->image && File::exists(public_path($destination->image))) {
                File::delete(public_path($destination->image));
            }
        }

        return $destination->update($data);
    }

    public function deleteDestination(Destination $destination)
    {
        if ($destination->image && File::exists(public_path($destination->image))) {
            File::delete(public_path($destination->image));
        }
        return $destination->delete();
    }
}
