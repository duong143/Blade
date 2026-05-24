<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreComboRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'content_images' => ['nullable', 'array'],
            'content_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'from_location' => ['nullable', 'string', 'max:255'],
            'to_location' => ['nullable', 'string', 'max:255'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:60'],
            'duration_nights' => ['required', 'integer', 'min:0', 'max:60'],
            'preorder_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'short_desc' => ['nullable', 'string', 'max:500'],
            'hotel_amenities' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'itinerary_detail' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],

            'departures' => ['required', 'array', 'min:1'],
            'departures.*.id' => ['nullable', 'integer'],
            'departures.*.start_date' => ['required', 'date'],
            'departures.*.end_date' => ['nullable', 'date'],
            'departures.*.capacity' => ['required', 'integer', 'min:1'],
            'departures.*.sold' => ['nullable', 'integer', 'min:0'],
            'departures.*.status' => ['nullable', 'in:0,1'],

            'departures.*.prices' => ['required', 'array'],
            'departures.*.prices.adult' => ['required', 'integer', 'min:0'],
            'departures.*.prices.child' => ['required', 'integer', 'min:0'],
            'departures.*.prices.infant' => ['required', 'integer', 'min:0'],

            'departures.*.sales' => ['nullable', 'array'],
            'departures.*.sales.*.id' => ['nullable', 'integer'],
            'departures.*.sales.*.start_date' => ['required', 'date'],
            'departures.*.sales.*.end_date' => ['required', 'date'],
            'departures.*.sales.*.sale_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'departures.*.sales.*.sale_label' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $departures = $this->input('departures', []);
            $usedStartDates = [];

            foreach ($departures as $departureIndex => $departure) {
                $startDate = $departure['start_date'] ?? null;
                $endDate = $departure['end_date'] ?? null;
                $capacity = (int) ($departure['capacity'] ?? 0);
                $sold = (int) ($departure['sold'] ?? 0);

                if ($startDate) {
                    if (in_array($startDate, $usedStartDates, true)) {
                        $validator->errors()->add(
                            "departures.$departureIndex.start_date",
                            'Ngày khởi hành bị trùng trong cùng combo.'
                        );
                    }

                    $usedStartDates[] = $startDate;
                }

                if ($startDate && $endDate && $endDate < $startDate) {
                    $validator->errors()->add(
                        "departures.$departureIndex.end_date",
                        'Ngày khách được chọn đi đến phải lớn hơn hoặc bằng ngày bắt đầu.'
                    );
                }

                if ($sold > $capacity) {
                    $validator->errors()->add(
                        "departures.$departureIndex.sold",
                        'Đã bán không được lớn hơn số lượng.'
                    );
                }

                foreach (($departure['sales'] ?? []) as $saleIndex => $sale) {
                    $saleStart = $sale['start_date'] ?? null;
                    $saleEnd = $sale['end_date'] ?? null;
                    $salePercent = (int) ($sale['sale_percent'] ?? 0);

                    if (($saleStart && !$saleEnd) || (!$saleStart && $saleEnd)) {
                        $validator->errors()->add(
                            "departures.$departureIndex.sales.$saleIndex.start_date",
                            'Sale phải có đủ ngày bắt đầu và ngày kết thúc.'
                        );
                    }

                    if ($saleStart && $saleEnd && $saleEnd < $saleStart) {
                        $validator->errors()->add(
                            "departures.$departureIndex.sales.$saleIndex.end_date",
                            'Ngày kết thúc sale phải lớn hơn hoặc bằng ngày bắt đầu.'
                        );
                    }

                    if ($salePercent < 0 || $salePercent > 100) {
                        $validator->errors()->add(
                            "departures.$departureIndex.sales.$saleIndex.sale_percent",
                            'Phần trăm sale phải từ 0 đến 100.'
                        );
                    }
                }
            }
        });
    }
}
