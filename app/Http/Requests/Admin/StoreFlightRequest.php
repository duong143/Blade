<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreFlightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'airline_id' => ['required', 'integer', 'exists:airlines,id'],
            'departure_airport_id' => ['required', 'integer', 'exists:airports,id'],
            'arrival_airport_id' => ['required', 'integer', 'exists:airports,id'],
            'flight_number' => ['required', 'string', 'max:30'],
            'aircraft' => ['nullable', 'string', 'max:255'],
            'seat_layout' => ['nullable', 'string', 'max:255'],
            'seat_pitch' => ['nullable', 'string', 'max:255'],
            'departure_date' => ['required', 'date'],
            'departure_time' => ['required'],
            'arrival_date' => ['required', 'date'],
            'arrival_time' => ['required'],
            'duration_minutes' => ['required', 'integer', 'min:0'],
            'seat_class' => ['required', 'string', 'max:100'],
            'adult_price' => ['required', 'integer', 'min:0'],
            'child_price' => ['nullable', 'integer', 'min:0'],
            'infant_price' => ['nullable', 'integer', 'min:0'],
            'tax_fee' => ['nullable', 'integer', 'min:0'],
            'carry_on_baggage' => ['nullable', 'string', 'max:255'],
            'checked_baggage' => ['nullable', 'string', 'max:255'],
            'other_benefits' => ['nullable', 'string'],
            'fare_points' => ['nullable', 'string', 'max:100'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'total_seats' => ['required', 'integer', 'min:0'],
            'available_seats' => ['required', 'integer', 'min:0'],
            'is_direct' => ['required', 'in:0,1'],
            'is_active' => ['required', 'in:0,1'],

            'price_items' => ['nullable', 'array'],
            'price_items.*.id' => ['nullable', 'integer'],
            'price_items.*.label' => ['required_with:price_items', 'string', 'max:255'],
            'price_items.*.amount' => ['required_with:price_items', 'integer', 'min:0'],
            'price_items.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'price_items.*.is_active' => ['nullable', 'in:0,1'],

            'conditions' => ['nullable', 'array'],
            'conditions.*.id' => ['nullable', 'integer'],
            'conditions.*.label' => ['required_with:conditions', 'string', 'max:255'],
            'conditions.*.value' => ['nullable', 'string', 'max:255'],
            'conditions.*.is_enabled' => ['nullable', 'in:0,1'],
            'conditions.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'conditions.*.is_active' => ['nullable', 'in:0,1'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator) {
            $departureAirportId = (int) $this->input('departure_airport_id');
            $arrivalAirportId = (int) $this->input('arrival_airport_id');
            $availableSeats = (int) $this->input('available_seats', 0);
            $totalSeats = (int) $this->input('total_seats', 0);

            if ($departureAirportId === $arrivalAirportId) {
                $validator->errors()->add(
                    'arrival_airport_id',
                    'Sân bay đến không được trùng sân bay đi.'
                );
            }

            if ($availableSeats > $totalSeats) {
                $validator->errors()->add(
                    'available_seats',
                    'Số ghế còn lại không được lớn hơn tổng số ghế.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'airline_id.required' => 'Vui lòng chọn hãng bay.',
            'departure_airport_id.required' => 'Vui lòng chọn sân bay đi.',
            'arrival_airport_id.required' => 'Vui lòng chọn sân bay đến.',
            'flight_number.required' => 'Vui lòng nhập mã chuyến bay.',
            'departure_date.required' => 'Vui lòng chọn ngày đi.',
            'departure_time.required' => 'Vui lòng chọn giờ đi.',
            'arrival_date.required' => 'Vui lòng chọn ngày đến.',
            'arrival_time.required' => 'Vui lòng chọn giờ đến.',
            'duration_minutes.required' => 'Vui lòng nhập thời lượng chuyến bay.',
            'seat_class.required' => 'Vui lòng nhập hạng ghế.',
            'adult_price.required' => 'Vui lòng nhập giá người lớn.',
            'total_seats.required' => 'Vui lòng nhập tổng số ghế.',
            'available_seats.required' => 'Vui lòng nhập số ghế còn lại.',
            'is_direct.required' => 'Vui lòng chọn loại chuyến bay.',
            'is_active.required' => 'Vui lòng chọn trạng thái.',
        ];
    }
}
