<?php

namespace Database\Seeders;

use App\Models\Combo;
use App\Models\ComboDeparture;
use App\Models\ComboDeparturePrice;
use App\Models\ComboDepartureSale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComboBulkSeeder extends Seeder
{
    public function run(): void
    {
        $combos = [
            [
                'code' => 'DN002',
                'title' => 'Đà Nẵng - Bà Nà - Hội An',
                'from_location' => 'Hà Nội',
                'to_location' => 'Đà Nẵng',
                'duration_days' => 4,
                'duration_nights' => 3,
                'short_desc' => '<p>Combo nghỉ dưỡng Đà Nẵng 4 ngày 3 đêm, khám phá Bà Nà và phố cổ Hội An.</p>',
                'hotel_amenities' => '<p>Khách sạn 4 sao, buffet sáng, hồ bơi, đưa đón sân bay.</p>',
                'description' => '<p>Trải nghiệm kỳ nghỉ hấp dẫn tại Đà Nẵng với lịch trình linh hoạt, phù hợp gia đình và nhóm bạn.</p>',
                'itinerary_detail' => '<p>Ngày 1 nhận phòng - Ngày 2 tham quan Bà Nà - Ngày 3 Hội An - Ngày 4 trả phòng.</p>',
                'preorder_days' => 5,
                'status' => true,
                'departures' => [
                    [
                        'start_date' => '2026-04-10',
                        'end_date' => '2026-04-13',
                        'capacity' => 30,
                        'sold' => 8,
                        'adult_price' => 2890000,
                        'child_price' => 2190000,
                        'infant_price' => 450000,
                        'sale_percent' => 15,
                        'sale_label' => 'Sale đầu hè',
                    ],
                    [
                        'start_date' => '2026-04-17',
                        'end_date' => '2026-04-20',
                        'capacity' => 30,
                        'sold' => 12,
                        'adult_price' => 2990000,
                        'child_price' => 2290000,
                        'infant_price' => 450000,
                        'sale_percent' => 10,
                        'sale_label' => 'Ưu đãi tuần',
                    ],
                ],
            ],
            [
                'code' => 'PQ001',
                'title' => 'Phú Quốc nghỉ dưỡng biển',
                'from_location' => 'Hồ Chí Minh',
                'to_location' => 'Phú Quốc',
                'duration_days' => 3,
                'duration_nights' => 2,
                'short_desc' => '<p>Combo Phú Quốc nghỉ dưỡng biển 3N2Đ, phù hợp cặp đôi và gia đình.</p>',
                'hotel_amenities' => '<p>Resort gần biển, buffet sáng, hồ bơi, miễn phí xe đưa đón.</p>',
                'description' => '<p>Tận hưởng kỳ nghỉ tại Phú Quốc với không gian nghỉ dưỡng cao cấp và lịch trình thoải mái.</p>',
                'itinerary_detail' => '<p>Ngày 1 nhận phòng - Ngày 2 vui chơi tự do - Ngày 3 trả phòng.</p>',
                'preorder_days' => 7,
                'status' => true,
                'departures' => [
                    [
                        'start_date' => '2026-04-12',
                        'end_date' => '2026-04-14',
                        'capacity' => 24,
                        'sold' => 6,
                        'adult_price' => 3290000,
                        'child_price' => 2590000,
                        'infant_price' => 500000,
                        'sale_percent' => 20,
                        'sale_label' => 'Flash sale',
                    ],
                    [
                        'start_date' => '2026-04-19',
                        'end_date' => '2026-04-21',
                        'capacity' => 24,
                        'sold' => 9,
                        'adult_price' => 3390000,
                        'child_price' => 2690000,
                        'infant_price' => 500000,
                        'sale_percent' => 10,
                        'sale_label' => 'Ưu đãi cuối tuần',
                    ],
                ],
            ],
            [
                'code' => 'NT001',
                'title' => 'Nha Trang biển xanh',
                'from_location' => 'Hà Nội',
                'to_location' => 'Nha Trang',
                'duration_days' => 4,
                'duration_nights' => 3,
                'short_desc' => '<p>Combo Nha Trang 4N3Đ nghỉ dưỡng và tham quan thành phố biển.</p>',
                'hotel_amenities' => '<p>Khách sạn trung tâm, gần biển, ăn sáng, hồ bơi.</p>',
                'description' => '<p>Khám phá Nha Trang với lịch trình nghỉ dưỡng kết hợp tham quan linh hoạt.</p>',
                'itinerary_detail' => '<p>Ngày 1 nhận phòng - Ngày 2 city tour - Ngày 3 vui chơi tự do - Ngày 4 trả phòng.</p>',
                'preorder_days' => 5,
                'status' => true,
                'departures' => [
                    [
                        'start_date' => '2026-04-11',
                        'end_date' => '2026-04-14',
                        'capacity' => 28,
                        'sold' => 7,
                        'adult_price' => 2790000,
                        'child_price' => 2090000,
                        'infant_price' => 400000,
                        'sale_percent' => 12,
                        'sale_label' => 'Mở bán sớm',
                    ],
                    [
                        'start_date' => '2026-04-18',
                        'end_date' => '2026-04-21',
                        'capacity' => 28,
                        'sold' => 13,
                        'adult_price' => 2890000,
                        'child_price' => 2190000,
                        'infant_price' => 400000,
                        'sale_percent' => 8,
                        'sale_label' => 'Ưu đãi nhẹ',
                    ],
                ],
            ],
            [
                'code' => 'DL001',
                'title' => 'Đà Lạt ngàn hoa',
                'from_location' => 'Hồ Chí Minh',
                'to_location' => 'Đà Lạt',
                'duration_days' => 3,
                'duration_nights' => 2,
                'short_desc' => '<p>Combo Đà Lạt 3N2Đ dành cho kỳ nghỉ cuối tuần thư giãn.</p>',
                'hotel_amenities' => '<p>Khách sạn trung tâm, buffet sáng, hỗ trợ thuê xe.</p>',
                'description' => '<p>Tham quan Đà Lạt với khí hậu mát mẻ, thích hợp nghỉ dưỡng ngắn ngày.</p>',
                'itinerary_detail' => '<p>Ngày 1 check-in - Ngày 2 tham quan - Ngày 3 mua sắm và về.</p>',
                'preorder_days' => 3,
                'status' => true,
                'departures' => [
                    [
                        'start_date' => '2026-04-09',
                        'end_date' => '2026-04-11',
                        'capacity' => 20,
                        'sold' => 5,
                        'adult_price' => 1890000,
                        'child_price' => 1390000,
                        'infant_price' => 300000,
                        'sale_percent' => 10,
                        'sale_label' => 'Combo hot',
                    ],
                    [
                        'start_date' => '2026-04-16',
                        'end_date' => '2026-04-18',
                        'capacity' => 20,
                        'sold' => 11,
                        'adult_price' => 1990000,
                        'child_price' => 1490000,
                        'infant_price' => 300000,
                        'sale_percent' => 5,
                        'sale_label' => 'Giá tốt',
                    ],
                ],
            ],
            [
                'code' => 'QN001',
                'title' => 'Quy Nhơn - Kỳ Co - Eo Gió',
                'from_location' => 'Hà Nội',
                'to_location' => 'Quy Nhơn',
                'duration_days' => 4,
                'duration_nights' => 3,
                'short_desc' => '<p>Combo Quy Nhơn 4N3Đ với các điểm check-in nổi bật.</p>',
                'hotel_amenities' => '<p>Khách sạn gần biển, ăn sáng, hỗ trợ tour hằng ngày.</p>',
                'description' => '<p>Khám phá vẻ đẹp biển Quy Nhơn với lịch trình hợp lý và chi phí tiết kiệm.</p>',
                'itinerary_detail' => '<p>Ngày 1 nhận phòng - Ngày 2 Kỳ Co - Ngày 3 Eo Gió - Ngày 4 trả phòng.</p>',
                'preorder_days' => 6,
                'status' => true,
                'departures' => [
                    [
                        'start_date' => '2026-04-13',
                        'end_date' => '2026-04-16',
                        'capacity' => 26,
                        'sold' => 10,
                        'adult_price' => 2590000,
                        'child_price' => 1990000,
                        'infant_price' => 380000,
                        'sale_percent' => 18,
                        'sale_label' => 'Siêu ưu đãi',
                    ],
                ],
            ],
            [
                'code' => 'HL001',
                'title' => 'Hạ Long du thuyền',
                'from_location' => 'Hà Nội',
                'to_location' => 'Hạ Long',
                'duration_days' => 2,
                'duration_nights' => 1,
                'short_desc' => '<p>Combo Hạ Long 2N1Đ trải nghiệm nghỉ đêm và tham quan vịnh.</p>',
                'hotel_amenities' => '<p>Du thuyền, bữa ăn theo chương trình, xe đón trả.</p>',
                'description' => '<p>Kỳ nghỉ ngắn ngày phù hợp gia đình và cặp đôi tại Hạ Long.</p>',
                'itinerary_detail' => '<p>Ngày 1 lên tàu - Ngày 2 tham quan và trở về.</p>',
                'preorder_days' => 2,
                'status' => true,
                'departures' => [
                    [
                        'start_date' => '2026-04-08',
                        'end_date' => '2026-04-09',
                        'capacity' => 18,
                        'sold' => 4,
                        'adult_price' => 2190000,
                        'child_price' => 1690000,
                        'infant_price' => 250000,
                        'sale_percent' => 7,
                        'sale_label' => 'Đặt sớm',
                    ],
                    [
                        'start_date' => '2026-04-15',
                        'end_date' => '2026-04-16',
                        'capacity' => 18,
                        'sold' => 8,
                        'adult_price' => 2290000,
                        'child_price' => 1790000,
                        'infant_price' => 250000,
                        'sale_percent' => 0,
                        'sale_label' => null,
                    ],
                ],
            ],
            [
                'code' => 'HP001',
                'title' => 'Hải Phòng - Cát Bà',
                'from_location' => 'Hà Nội',
                'to_location' => 'Hải Phòng',
                'duration_days' => 3,
                'duration_nights' => 2,
                'short_desc' => '<p>Combo Hải Phòng - Cát Bà 3N2Đ, nghỉ dưỡng biển gần Hà Nội.</p>',
                'hotel_amenities' => '<p>Khách sạn trung tâm, ăn sáng, hỗ trợ cano.</p>',
                'description' => '<p>Gói nghỉ biển thuận tiện, phù hợp gia đình và nhóm bạn.</p>',
                'itinerary_detail' => '<p>Ngày 1 di chuyển - Ngày 2 tham quan đảo - Ngày 3 trở về.</p>',
                'preorder_days' => 4,
                'status' => true,
                'departures' => [
                    [
                        'start_date' => '2026-04-14',
                        'end_date' => '2026-04-16',
                        'capacity' => 22,
                        'sold' => 3,
                        'adult_price' => 2090000,
                        'child_price' => 1590000,
                        'infant_price' => 280000,
                        'sale_percent' => 9,
                        'sale_label' => 'Khuyến mãi',
                    ],
                ],
            ],
            [
                'code' => 'HUE001',
                'title' => 'Huế di sản cố đô',
                'from_location' => 'Hồ Chí Minh',
                'to_location' => 'Huế',
                'duration_days' => 3,
                'duration_nights' => 2,
                'short_desc' => '<p>Combo Huế 3N2Đ khám phá ẩm thực và di sản.</p>',
                'hotel_amenities' => '<p>Khách sạn trung tâm, buffet sáng, hỗ trợ city tour.</p>',
                'description' => '<p>Trải nghiệm nét đẹp cổ kính của Huế với chương trình linh hoạt.</p>',
                'itinerary_detail' => '<p>Ngày 1 nhận phòng - Ngày 2 tham quan Đại Nội - Ngày 3 trả phòng.</p>',
                'preorder_days' => 5,
                'status' => true,
                'departures' => [
                    [
                        'start_date' => '2026-04-20',
                        'end_date' => '2026-04-22',
                        'capacity' => 20,
                        'sold' => 6,
                        'adult_price' => 2390000,
                        'child_price' => 1790000,
                        'infant_price' => 320000,
                        'sale_percent' => 11,
                        'sale_label' => 'Ưu đãi mùa xuân',
                    ],
                ],
            ],
        ];

        foreach ($combos as $item) {
            $combo = Combo::updateOrCreate(
                ['code' => $item['code']],
                [
                    'code' => $item['code'],
                    'title' => $item['title'],
                    'slug' => Str::slug($item['title']),
                    'image' => null,
                    'content_image' => [],
                    'from_location' => $item['from_location'],
                    'to_location' => $item['to_location'],
                    'duration_days' => $item['duration_days'],
                    'duration_nights' => $item['duration_nights'],
                    'short_desc' => $item['short_desc'],
                    'hotel_amenities' => $item['hotel_amenities'],
                    'description' => $item['description'],
                    'itinerary_detail' => $item['itinerary_detail'],
                    'preorder_days' => $item['preorder_days'],
                    'status' => $item['status'],
                ]
            );

            foreach ($item['departures'] as $dep) {
                $departure = ComboDeparture::updateOrCreate(
                    [
                        'combo_id' => $combo->id,
                        'start_date' => $dep['start_date'],
                    ],
                    [
                        'combo_id' => $combo->id,
                        'start_date' => $dep['start_date'],
                        'end_date' => $dep['end_date'],
                        'capacity' => $dep['capacity'],
                        'sold' => $dep['sold'],
                        'status' => true,
                    ]
                );

                $prices = [
                    'adult' => $dep['adult_price'],
                    'child' => $dep['child_price'],
                    'infant' => $dep['infant_price'],
                ];

                foreach ($prices as $type => $price) {
                    ComboDeparturePrice::updateOrCreate(
                        [
                            'departure_id' => $departure->id,
                            'passenger_type' => $type,
                        ],
                        [
                            'base_price' => $price,
                        ]
                    );
                }

                if ((int) $dep['sale_percent'] > 0) {
                    $startDate = \Carbon\Carbon::parse($dep['start_date']);
                    $endDate = \Carbon\Carbon::parse($dep['end_date']);

                    while ($startDate->lte($endDate)) {
                        ComboDepartureSale::updateOrCreate(
                            [
                                'departure_id' => $departure->id,
                                'start_date' => $startDate->format('Y-m-d'),
                                'end_date' => $startDate->format('Y-m-d'),
                            ],
                            [
                                'sale_percent' => $dep['sale_percent'],
                                'sale_label' => $dep['sale_label'],
                            ]
                        );

                        $startDate->addDay();
                    }
                }
            }
        }
    }
}
