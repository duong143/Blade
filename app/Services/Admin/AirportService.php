<?php

namespace App\Services\Admin;

use App\Models\Airport;
use Illuminate\Http\Request;

class AirportService
{
    public function getPaginatedAirports(Request $request)
    {
        $query = Airport::query();

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('code', 'like', '%' . $keyword . '%')
                    ->orWhere('name', 'like', '%' . $keyword . '%')
                    ->orWhere('city', 'like', '%' . $keyword . '%')
                    ->orWhere('country', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (int) $request->is_active);
        }

        return $query->orderBy('city')->paginate(10)->withQueryString();
    }

    public function getAirportById(int $id): Airport
    {
        return Airport::findOrFail($id);
    }

    public function createAirport(array $data): Airport
    {
        return Airport::create([
            'code' => strtoupper(trim($data['code'])),
            'name' => trim($data['name']),
            'city' => trim($data['city']),
            'country' => trim($data['country'] ?? 'Việt Nam'),
            'is_active' => (int) $data['is_active'],
        ]);
    }

    public function updateAirport(Airport $airport, array $data): Airport
    {
        $airport->update([
            'code' => strtoupper(trim($data['code'])),
            'name' => trim($data['name']),
            'city' => trim($data['city']),
            'country' => trim($data['country'] ?? 'Việt Nam'),
            'is_active' => (int) $data['is_active'],
        ]);

        return $airport;
    }

    public function deleteAirport(Airport $airport): void
    {
        $airport->delete();
    }

    public function toggleStatus(Airport $airport): Airport
    {
        $airport->is_active = !$airport->is_active;
        $airport->save();

        return $airport;
    }
}
