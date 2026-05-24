<?php

namespace App\Services\Admin;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\FlightCondition;
use App\Models\FlightPriceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FlightService
{
    public function getPaginatedFlights(Request $request)
    {
        $query = Flight::query()
            ->with(['airline', 'departureAirport', 'arrivalAirport']);

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('flight_number', 'like', '%' . $keyword . '%')
                    ->orWhere('aircraft', 'like', '%' . $keyword . '%')
                    ->orWhere('seat_class', 'like', '%' . $keyword . '%')
                    ->orWhereHas('airline', function ($sub) use ($keyword) {
                        $sub->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('code', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('departureAirport', function ($sub) use ($keyword) {
                        $sub->where('code', 'like', '%' . $keyword . '%')
                            ->orWhere('city', 'like', '%' . $keyword . '%')
                            ->orWhere('name', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('arrivalAirport', function ($sub) use ($keyword) {
                        $sub->where('code', 'like', '%' . $keyword . '%')
                            ->orWhere('city', 'like', '%' . $keyword . '%')
                            ->orWhere('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        if ($request->filled('airline_id')) {
            $query->where('airline_id', (int) $request->airline_id);
        }

        if ($request->filled('departure_airport_id')) {
            $query->where('departure_airport_id', (int) $request->departure_airport_id);
        }

        if ($request->filled('arrival_airport_id')) {
            $query->where('arrival_airport_id', (int) $request->arrival_airport_id);
        }

        if ($request->filled('departure_date')) {
            $query->whereDate('departure_date', $request->departure_date);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (int) $request->is_active);
        }

        return $query
            ->orderByDesc('departure_date')
            ->orderBy('display_order')
            ->orderBy('departure_time')
            ->paginate(10)
            ->withQueryString();
    }

    public function getFormData(): array
    {
        $airlines = Airline::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $airports = Airport::query()
            ->where('is_active', true)
            ->orderBy('city')
            ->get();

        return compact('airlines', 'airports');
    }

    public function getFlightForEdit(int $id): Flight
    {
        return Flight::with([
            'priceItems' => function ($q) {
                $q->orderBy('sort_order')->orderBy('id');
            },
            'conditions' => function ($q) {
                $q->orderBy('sort_order')->orderBy('id');
            },
        ])->findOrFail($id);
    }

    public function createFlight(array $data): Flight
    {
        return DB::transaction(function () use ($data) {
            $flight = Flight::create($this->extractFlightData($data));
            $this->syncPriceItems($flight, $data['price_items'] ?? []);
            $this->syncConditions($flight, $data['conditions'] ?? []);

            return $flight;
        });
    }

    public function updateFlight(Flight $flight, array $data): Flight
    {
        return DB::transaction(function () use ($flight, $data) {
            $flight->update($this->extractFlightData($data));
            $this->syncPriceItems($flight, $data['price_items'] ?? []);
            $this->syncConditions($flight, $data['conditions'] ?? []);

            return $flight;
        });
    }

    public function deleteFlight(Flight $flight): void
    {
        $flight->delete();
    }

    public function toggleStatus(Flight $flight): Flight
    {
        $flight->is_active = !$flight->is_active;
        $flight->save();

        return $flight;
    }

    protected function extractFlightData(array $data): array
    {
        return [
            'airline_id' => (int) $data['airline_id'],
            'departure_airport_id' => (int) $data['departure_airport_id'],
            'arrival_airport_id' => (int) $data['arrival_airport_id'],
            'flight_number' => trim($data['flight_number']),
            'aircraft' => $data['aircraft'] ?? null,
            'seat_layout' => $data['seat_layout'] ?? null,
            'seat_pitch' => $data['seat_pitch'] ?? null,
            'departure_date' => $data['departure_date'],
            'departure_time' => $data['departure_time'],
            'arrival_date' => $data['arrival_date'],
            'arrival_time' => $data['arrival_time'],
            'duration_minutes' => (int) $data['duration_minutes'],
            'seat_class' => trim($data['seat_class']),
            'adult_price' => (int) $data['adult_price'],
            'child_price' => (int) ($data['child_price'] ?? 0),
            'infant_price' => (int) ($data['infant_price'] ?? 0),
            'tax_fee' => (int) ($data['tax_fee'] ?? 0),
            'carry_on_baggage' => $data['carry_on_baggage'] ?? null,
            'checked_baggage' => $data['checked_baggage'] ?? null,
            'other_benefits' => $data['other_benefits'] ?? null,
            'fare_points' => $data['fare_points'] ?? null,
            'display_order' => (int) ($data['display_order'] ?? 0),
            'total_seats' => (int) $data['total_seats'],
            'available_seats' => (int) $data['available_seats'],
            'is_direct' => (int) $data['is_direct'],
            'is_active' => (int) $data['is_active'],
        ];
    }

    protected function syncPriceItems(Flight $flight, array $items): void
    {
        $keptIds = [];

        foreach ($items as $index => $item) {
            if (!isset($item['label']) || trim((string) $item['label']) === '') {
                continue;
            }

            $itemId = $item['id'] ?? null;

            if ($itemId) {
                $priceItem = $flight->priceItems()->where('id', $itemId)->first();

                if (!$priceItem) {
                    throw ValidationException::withMessages([
                        "price_items.$index.id" => 'Dòng giá vé không hợp lệ.',
                    ]);
                }

                $priceItem->update([
                    'label' => trim((string) $item['label']),
                    'amount' => (int) ($item['amount'] ?? 0),
                    'sort_order' => (int) ($item['sort_order'] ?? $index),
                    'is_active' => (int) ($item['is_active'] ?? 1),
                ]);
            } else {
                $priceItem = $flight->priceItems()->create([
                    'label' => trim((string) $item['label']),
                    'amount' => (int) ($item['amount'] ?? 0),
                    'sort_order' => (int) ($item['sort_order'] ?? $index),
                    'is_active' => (int) ($item['is_active'] ?? 1),
                ]);
            }

            $keptIds[] = $priceItem->id;
        }

        $flight->priceItems()->whereNotIn('id', $keptIds)->delete();
    }

    protected function syncConditions(Flight $flight, array $items): void
    {
        $keptIds = [];

        foreach ($items as $index => $item) {
            if (!isset($item['label']) || trim((string) $item['label']) === '') {
                continue;
            }

            $itemId = $item['id'] ?? null;

            if ($itemId) {
                $condition = $flight->conditions()->where('id', $itemId)->first();

                if (!$condition) {
                    throw ValidationException::withMessages([
                        "conditions.$index.id" => 'Điều kiện vé không hợp lệ.',
                    ]);
                }

                $condition->update([
                    'label' => trim((string) $item['label']),
                    'value' => $item['value'] ?? null,
                    'is_enabled' => (int) ($item['is_enabled'] ?? 1),
                    'sort_order' => (int) ($item['sort_order'] ?? $index),
                    'is_active' => (int) ($item['is_active'] ?? 1),
                ]);
            } else {
                $condition = $flight->conditions()->create([
                    'label' => trim((string) $item['label']),
                    'value' => $item['value'] ?? null,
                    'is_enabled' => (int) ($item['is_enabled'] ?? 1),
                    'sort_order' => (int) ($item['sort_order'] ?? $index),
                    'is_active' => (int) ($item['is_active'] ?? 1),
                ]);
            }

            $keptIds[] = $condition->id;
        }

        $flight->conditions()->whereNotIn('id', $keptIds)->delete();
    }
}