<?php

namespace App\Services\Admin;

use App\Models\Airline;
use Illuminate\Http\Request;

class AirlineService
{
    public function getPaginatedAirlines(Request $request)
    {
        $query = Airline::query();

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (int) $request->is_active);
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function getAirlineById(int $id): Airline
    {
        return Airline::findOrFail($id);
    }

    public function createAirline(array $data, Request $request): Airline
    {
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('airlines', 'public');
        }

        return Airline::create($data);
    }

    public function updateAirline(Airline $airline, array $data, Request $request): Airline
    {
        if ($request->hasFile('logo')) {
            $this->deleteSingleFile($airline->logo);
            $data['logo'] = $request->file('logo')->store('airlines', 'public');
        }

        $airline->update($data);

        return $airline;
    }

    public function deleteAirline(Airline $airline): void
    {
        $this->deleteSingleFile($airline->logo);
        $airline->delete();
    }

    public function toggleStatus(Airline $airline): Airline
    {
        $airline->is_active = !$airline->is_active;
        $airline->save();

        return $airline;
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
