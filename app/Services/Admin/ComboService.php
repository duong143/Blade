<?php

namespace App\Services\Admin;

use App\Models\Combo;
use App\Models\ComboDeparture;
use App\Models\ComboDeparturePrice;
use App\Models\ComboDepartureSale;
use App\Models\ComboImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ComboService
{
    public function getPaginatedCombos(Request $request)
    {
        $query = Combo::query()
            ->with(['departures' => function ($q) {
                $q->orderBy('start_date', 'asc');
            }]);

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('code', 'like', '%' . $keyword . '%')
                    ->orWhere('title', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_location')) {
            $query->where('from_location', 'like', '%' . trim((string) $request->from_location) . '%');
        }

        if ($request->filled('to_location')) {
            $query->where('to_location', 'like', '%' . trim((string) $request->to_location) . '%');
        }

        if ($request->filled('has_departure')) {
            if ($request->has_departure === '1') {
                $query->has('departures');
            } elseif ($request->has_departure === '0') {
                $query->doesntHave('departures');
            }
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function getComboForEdit(int $id): Combo
    {
        return Combo::with([
            'images',
            'departures' => function ($q) {
                $q->orderBy('start_date', 'asc');
            },
            'departures.prices',
            'departures.sales' => function ($q) {
                $q->orderBy('start_date', 'asc');
            },
        ])->findOrFail($id);
    }

    public function createCombo(array $data, Request $request): Combo
    {
        $prepared = $this->prepareDataForStore($data, $request);

        return DB::transaction(function () use ($prepared, $request) {
            $combo = Combo::create(
                collect($prepared)->except(['departures', 'stored_content_images'])->toArray()
            );

            if (!empty($prepared['stored_content_images'])) {
                $combo->content_image = $prepared['stored_content_images'];
                $combo->save();
            }

            $this->storeGalleryImages($combo, $request);
            $this->syncDepartures($combo, $prepared['departures'] ?? []);

            return $combo;
        });
    }

    public function updateCombo(Combo $combo, array $data, Request $request): Combo
    {
        $prepared = $this->prepareDataForUpdate($combo, $data, $request);

        return DB::transaction(function () use ($combo, $prepared, $request) {
            $combo->update(
                collect($prepared)->except(['departures', 'stored_content_images'])->toArray()
            );

            if (array_key_exists('stored_content_images', $prepared)) {
                $combo->content_image = $prepared['stored_content_images'];
                $combo->save();
            }

            $this->storeGalleryImages($combo, $request, true);
            $this->syncDepartures($combo, $prepared['departures'] ?? []);

            return $combo;
        });
    }

    public function deleteCombo(int $id): void
    {
        $combo = Combo::with('images')->findOrFail($id);

        $this->deleteSingleFile($combo->image);

        $contentImages = is_array($combo->content_image) ? $combo->content_image : [];
        foreach ($contentImages as $path) {
            $this->deleteSingleFile($path);
        }

        foreach ($combo->images as $img) {
            $this->deleteSingleFile($img->image_path);
        }

        $combo->delete();
    }

    public function deleteImage(int $id): void
    {
        $image = ComboImage::findOrFail($id);

        $this->deleteSingleFile($image->image_path);
        $image->delete();
    }

    public function deleteContentImage(int $comboId, int $index): void
    {
        $combo = Combo::findOrFail($comboId);

        $contentImages = is_array($combo->content_image) ? $combo->content_image : [];

        if (!array_key_exists($index, $contentImages)) {
            throw ValidationException::withMessages([
                'content_image' => 'Ảnh nội dung không tồn tại',
            ]);
        }

        $imagePath = $contentImages[$index] ?? null;
        $this->deleteSingleFile($imagePath);

        unset($contentImages[$index]);
        $combo->content_image = array_values($contentImages);
        $combo->save();
    }

    public function toggleStatus(int $id): array
    {
        $combo = Combo::findOrFail($id);
        $combo->status = !$combo->status;
        $combo->save();

        return [
            'success' => true,
            'status' => (bool) $combo->status,
        ];
    }

    protected function prepareDataForStore(array $data, Request $request): array
    {
        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('combos', 'public');
        }

        if (empty($data['code'])) {
            $data['code'] = 'CB' . now()->format('YmdHis');
        }

        $contentImages = [];

        if ($request->hasFile('content_images')) {
            foreach ($request->file('content_images') as $file) {
                $contentImages[] = $file->store('combos/content', 'public');
            }
        }

        if (!empty($contentImages)) {
            $data['stored_content_images'] = $contentImages;
        }

        return $data;
    }

    protected function prepareDataForUpdate(Combo $combo, array $data, Request $request): array
    {
        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('image')) {
            if (!empty($combo->image)) {
                $this->deleteSingleFile($combo->image);
            }

            $data['image'] = $request->file('image')->store('combos', 'public');
        }

        if ($request->hasFile('content_images')) {
            $contentImages = is_array($combo->content_image) ? $combo->content_image : [];

            foreach ($request->file('content_images') as $file) {
                $contentImages[] = $file->store('combos/content', 'public');
            }

            $data['stored_content_images'] = $contentImages;
        }

        if (empty($data['code'])) {
            $data['code'] = $combo->code;
        }

        return $data;
    }

    protected function storeGalleryImages(Combo $combo, Request $request, bool $append = false): void
    {
        if (!$request->hasFile('gallery_images')) {
            return;
        }

        $currentMaxSort = $append ? (int) ($combo->images()->max('sort_order') ?? -1) : -1;

        foreach ($request->file('gallery_images') as $index => $imageFile) {
            $path = $imageFile->store('combos/gallery', 'public');

            ComboImage::create([
                'combo_id' => $combo->id,
                'image_path' => $path,
                'is_cover' => false,
                'sort_order' => $currentMaxSort + $index + 1,
            ]);
        }
    }

    protected function syncDepartures(Combo $combo, array $departures): void
    {
        $keptDepartureIds = [];
        $usedStartDates = [];

        foreach ($departures as $index => $departureData) {
            $startDate = $departureData['start_date'] ?? null;

            if (in_array($startDate, $usedStartDates, true)) {
                throw ValidationException::withMessages([
                    "departures.$index.start_date" => 'Ngày khởi hành bị trùng trong cùng combo.',
                ]);
            }

            $usedStartDates[] = $startDate;

            $capacity = (int) ($departureData['capacity'] ?? 0);
            $sold = (int) ($departureData['sold'] ?? 0);

            if ($sold > $capacity) {
                throw ValidationException::withMessages([
                    "departures.$index.sold" => 'Đã bán không được lớn hơn số lượng.',
                ]);
            }

            $departureId = $departureData['id'] ?? null;

            if ($departureId) {
                $departure = $combo->departures()->where('id', $departureId)->first();

                if (!$departure) {
                    throw ValidationException::withMessages([
                        "departures.$index.id" => 'Đợt khởi hành không hợp lệ.',
                    ]);
                }

                $existsSameDate = $combo->departures()
                    ->where('id', '!=', $departure->id)
                    ->where('start_date', $startDate)
                    ->exists();

                if ($existsSameDate) {
                    throw ValidationException::withMessages([
                        "departures.$index.start_date" => 'Ngày khởi hành này đã tồn tại cho combo.',
                    ]);
                }

                $departure->update([
                    'start_date' => $startDate,
                    'end_date' => $departureData['end_date'] ?? null,
                    'capacity' => $capacity,
                    'sold' => $sold,
                    'status' => (int) ($departureData['status'] ?? 1),
                ]);
            } else {
                $existsSameDate = $combo->departures()
                    ->where('start_date', $startDate)
                    ->exists();

                if ($existsSameDate) {
                    throw ValidationException::withMessages([
                        "departures.$index.start_date" => 'Ngày khởi hành này đã tồn tại cho combo.',
                    ]);
                }

                $departure = $combo->departures()->create([
                    'start_date' => $startDate,
                    'end_date' => $departureData['end_date'] ?? null,
                    'capacity' => $capacity,
                    'sold' => $sold,
                    'status' => (int) ($departureData['status'] ?? 1),
                ]);
            }

            $keptDepartureIds[] = $departure->id;

            $this->syncDeparturePrices($departure, $departureData['prices'] ?? []);
            $this->syncDepartureSales($departure, $departureData['sales'] ?? []);
        }

        if (!empty($keptDepartureIds)) {
            $combo->departures()->whereNotIn('id', $keptDepartureIds)->delete();
        }
    }

    protected function syncDeparturePrices(ComboDeparture $departure, array $prices): void
    {
        foreach (['adult', 'child', 'infant'] as $passengerType) {
            ComboDeparturePrice::updateOrCreate(
                [
                    'departure_id' => $departure->id,
                    'passenger_type' => $passengerType,
                ],
                [
                    'base_price' => (int) ($prices[$passengerType] ?? 0),
                ]
            );
        }
    }

    protected function syncDepartureSales(ComboDeparture $departure, array $sales): void
    {
        $keptSaleIds = [];

        foreach ($sales as $saleIndex => $saleData) {
            $startDate = $saleData['start_date'] ?? null;
            $endDate = $saleData['end_date'] ?? null;
            $salePercent = (int) ($saleData['sale_percent'] ?? 0);

            if (!$startDate || !$endDate) {
                throw ValidationException::withMessages([
                    "departures.*.sales.$saleIndex.start_date" => 'Sale phải có ngày bắt đầu và ngày kết thúc.',
                ]);
            }

            if ($salePercent < 0 || $salePercent > 100) {
                throw ValidationException::withMessages([
                    "departures.*.sales.$saleIndex.sale_percent" => 'Phần trăm sale phải từ 0 đến 100.',
                ]);
            }

            $saleId = $saleData['id'] ?? null;

            if ($saleId) {
                $sale = $departure->sales()->where('id', $saleId)->first();

                if (!$sale) {
                    throw ValidationException::withMessages([
                        "departures.*.sales.$saleIndex.id" => 'Sale không hợp lệ.',
                    ]);
                }

                $sale->update([
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'sale_percent' => $salePercent,
                    'sale_label' => $saleData['sale_label'] ?? null,
                ]);
            } else {
                $sale = $departure->sales()->create([
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'sale_percent' => $salePercent,
                    'sale_label' => $saleData['sale_label'] ?? null,
                ]);
            }

            $keptSaleIds[] = $sale->id;
        }

        if (!empty($keptSaleIds)) {
            $departure->sales()->whereNotIn('id', $keptSaleIds)->delete();
        } else {
            $departure->sales()->delete();
        }
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
