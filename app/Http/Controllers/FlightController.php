<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function search(Request $request)
    {
        $from = trim((string) $request->query('from', ''));
        $to = trim((string) $request->query('to', ''));
        $fromText = trim((string) $request->query('from_text', ''));
        $toText = trim((string) $request->query('to_text', ''));
        $departureDate = trim((string) $request->query('departure_date', ''));
        $returnDate = trim((string) $request->query('return_date', ''));
        $tripType = trim((string) $request->query('trip_type', 'oneway'));

        $fromAirport = $from !== ''
            ? Airport::query()->where('code', $from)->where('is_active', true)->first()
            : null;

        $toAirport = $to !== ''
            ? Airport::query()->where('code', $to)->where('is_active', true)->first()
            : null;

        $airports = Airport::query()
            ->where('is_active', true)
            ->orderBy('city')
            ->get();

        $baseDepartureQuery = Flight::query()
            ->with([
                'airline',
                'departureAirport',
                'arrivalAirport',
                'priceItems' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
                },
                'conditions' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
                },
            ])
            ->where('is_active', true)
            ->where('available_seats', '>', 0);

        if ($from !== '') {
            $baseDepartureQuery->whereHas('departureAirport', function ($q) use ($from) {
                $q->where('code', $from);
            });
        }

        if ($to !== '') {
            $baseDepartureQuery->whereHas('arrivalAirport', function ($q) use ($to) {
                $q->where('code', $to);
            });
        }

        if ($departureDate !== '') {
            $baseDepartureQuery->whereDate('departure_date', $departureDate);
        }

        $departureFlights = (clone $baseDepartureQuery)
            ->orderBy('display_order')
            ->orderBy('adult_price')
            ->orderBy('departure_time')
            ->get();

        $calendarBaseDate = $departureDate !== ''
            ? \Carbon\Carbon::parse($departureDate)
            : now();

        $calendarStartDate = $calendarBaseDate->copy()->startOfMonth();
        $calendarEndDate = $calendarBaseDate->copy()->endOfMonth();

        $dateFlightGroups = Flight::query()
            ->with(['departureAirport', 'arrivalAirport'])
            ->where('is_active', true)
            ->where('available_seats', '>', 0)
            ->when($from !== '', function ($q) use ($from) {
                $q->whereHas('departureAirport', function ($sub) use ($from) {
                    $sub->where('code', $from);
                });
            })
            ->when($to !== '', function ($q) use ($to) {
                $q->whereHas('arrivalAirport', function ($sub) use ($to) {
                    $sub->where('code', $to);
                });
            })
            ->whereBetween('departure_date', [
                $calendarStartDate->toDateString(),
                $calendarEndDate->toDateString()
            ])
            ->orderBy('departure_date')
            ->get()
            ->groupBy(function ($flight) {
                return optional($flight->departure_date)->format('Y-m-d');
            });

        $departureDateOptions = collect();

        $currentDate = $calendarStartDate->copy();

        while ($currentDate->lte($calendarEndDate)) {
            $date = $currentDate->format('Y-m-d');
            $flights = $dateFlightGroups->get($date, collect());

            $minPrice = $flights->isNotEmpty()
                ? $flights->min(function ($flight) {
                    return (int) $flight->adult_price + (int) $flight->tax_fee;
                })
                : null;

            $query = [
                'from' => $from,
                'to' => $to,
                'from_text' => $fromText,
                'to_text' => $toText,
                'departure_date' => $date,
                'trip_type' => $tripType,
            ];

            if ($returnDate !== '') {
                $query['return_date'] = $returnDate;
            }

            $departureDateOptions->push([
                'date' => $date,
                'label' => $currentDate->format('d \T\h m'),
                'price_text' => $minPrice !== null
                    ? number_format((int) $minPrice, 0, ',', '.') . ' VND'
                    : '',
                'active' => $departureDate === $date,
                'url' => route('flight.search', $query),
            ]);

            $currentDate->addDay();
        }

        $returnFlights = collect();

        if (
            $tripType === 'roundtrip' &&
            $from !== '' &&
            $to !== '' &&
            $returnDate !== ''
        ) {
            $returnFlights = Flight::query()
                ->with([
                    'airline',
                    'departureAirport',
                    'arrivalAirport',
                    'priceItems' => function ($q) {
                        $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
                    },
                    'conditions' => function ($q) {
                        $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
                    },
                ])
                ->where('is_active', true)
                ->where('available_seats', '>', 0)
                ->whereDate('departure_date', $returnDate)
                ->whereHas('departureAirport', function ($q) use ($to) {
                    $q->where('code', $to);
                })
                ->whereHas('arrivalAirport', function ($q) use ($from) {
                    $q->where('code', $from);
                })
                ->orderBy('display_order')
                ->orderBy('adult_price')
                ->orderBy('departure_time')
                ->get();
        }

        $departureFlightCards = $departureFlights->map(function ($flight) {
            $priceDetail = $flight->priceItems->map(function ($item) {
                return [
                    'label' => $item->label,
                    'value' => (int) $item->amount,
                ];
            })->values()->toArray();

            if (empty($priceDetail)) {
                $priceDetail = [
                    [
                        'label' => 'Người lớn',
                        'value' => (int) $flight->adult_price,
                    ],
                    [
                        'label' => 'Thuế & phí',
                        'value' => (int) $flight->tax_fee,
                    ],
                ];
            }

            $conditions = $flight->conditions->map(function ($item) {
                return [
                    'label' => $item->label,
                    'value' => $item->value ?? '',
                    'enabled' => (bool) $item->is_enabled,
                ];
            })->values()->toArray();

            return [
                'id' => $flight->id,
                'airline' => $flight->airline->name ?? '',
                'logo' => !empty($flight->airline?->logo)
                    ? asset('storage/' . $flight->airline->logo)
                    : asset('images/Logo.png'),
                'code' => $flight->flight_number,
                'class' => $flight->seat_class,

                'departTime' => substr((string) $flight->departure_time, 0, 5),
                'departCity' => ($flight->departureAirport->city ?? '') . ' (' . ($flight->departureAirport->code ?? '') . ')',
                'departAirport' => ($flight->departureAirport->code ?? '') . '-' . ($flight->departureAirport->name ?? ''),

                'arriveTime' => substr((string) $flight->arrival_time, 0, 5),
                'arriveCity' => ($flight->arrivalAirport->city ?? '') . ' (' . ($flight->arrivalAirport->code ?? '') . ')',
                'arriveAirport' => ($flight->arrivalAirport->code ?? '') . '-' . ($flight->arrivalAirport->name ?? ''),

                'duration' => $flight->duration_text,
                'direct' => $flight->is_direct ? 'Bay thẳng' : 'Có điểm dừng',

                'price' => number_format((int) $flight->adult_price + (int) $flight->tax_fee, 0, ',', '.') . ' VND',
                'point' => $flight->fare_points ?? '',

                'aircraft' => $flight->aircraft ?: '',
                'seatLayout' => $flight->seat_layout ?: '',
                'seatPitch' => $flight->seat_pitch ?: '',
                'carryOn' => $flight->carry_on_baggage ?: '',
                'checkedBag' => $flight->checked_baggage ?: '',
                'convinient' => $flight->other_benefits ?: '',

                'adultPrice' => number_format((int) $flight->adult_price, 0, ',', '.') . ' VND',
                'taxPrice' => number_format((int) $flight->tax_fee, 0, ',', '.') . ' VND',
                'totalPriceText' => number_format((int) $flight->adult_price + (int) $flight->tax_fee, 0, ',', '.') . ' VND',
                'totalPrice' => (int) $flight->adult_price + (int) $flight->tax_fee,

                'priceDetail' => $priceDetail,
                'conditions' => $conditions,
            ];
        })->values();

        $returnFlightCards = $returnFlights->map(function ($flight) {
            return [
                'id' => $flight->id,
                'airline' => $flight->airline->name ?? '',
                'logo' => !empty($flight->airline?->logo)
                    ? asset('storage/' . $flight->airline->logo)
                    : asset('images/Logo.png'),
                'departTime' => substr((string) $flight->departure_time, 0, 5),
                'arriveTime' => substr((string) $flight->arrival_time, 0, 5),
                'duration' => $flight->duration_text,
                'from' => ($flight->departureAirport->city ?? '') . ' (' . ($flight->departureAirport->code ?? '') . ')',
                'to' => ($flight->arrivalAirport->city ?? '') . ' (' . ($flight->arrivalAirport->code ?? '') . ')',
                'direct' => $flight->is_direct ? 'Bay thẳng' : 'Có điểm dừng',
            ];
        })->values();

        return view('flightsearch', compact(
            'airports',
            'departureFlights',
            'returnFlights',
            'from',
            'to',
            'fromText',
            'toText',
            'departureDate',
            'returnDate',
            'tripType',
            'fromAirport',
            'toAirport',
            'departureDateOptions',
            'departureFlightCards',
            'returnFlightCards'
        ));
    }
}
