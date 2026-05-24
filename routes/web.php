<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

use App\Models\Combo;
use App\Models\ComboBooking;
use App\Models\DiscountCode;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\AttractionController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\SePayWebhookController;

/*
|--------------------------------------------------------------------------
| DISABLED / COMMENTED IMPORTS
|--------------------------------------------------------------------------
| Giữ lại để sau này cần dùng thì gỡ comment.
|--------------------------------------------------------------------------
*/

// use App\Http\Controllers\FlightController;
// use App\Models\FlightBooking;

// use App\Http\Controllers\Admin\FlightController as AdminFlightController;
// use App\Http\Controllers\Admin\AirlineController as AdminAirlineController;
// use App\Http\Controllers\Admin\AirportController as AdminAirportController;


/*
|--------------------------------------------------------------------------
| AUTH - USER FRONTEND
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', function () {
    session()->forget('user');

    return response()->json(['success' => true]);
});

Route::post('/change-password', function (Request $request) {
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = session('user');

    if (!$user) {
        return response()->json(['success' => false], 401);
    }

    $dbUser = \App\Models\User::find($user['id']);

    if (!Hash::check($request->current_password, $dbUser->password)) {
        return response()->json(['success' => false]);
    }

    $dbUser->password = Hash::make($request->new_password);
    $dbUser->save();

    return response()->json(['success' => true]);
});


/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
| Không dùng middleware admin.
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');


/*
|--------------------------------------------------------------------------
| ADMIN PANEL - CORE
|--------------------------------------------------------------------------
| Dashboard, roles, users, news, banners, settings.
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['admin'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */


        /*
            |--------------------------------------------
            | Dashboard
            -----------------------------------------------
        */

        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');



        /*
            |--------------------------------------------
            | Roles
            -----------------------------------------------
        */

        Route::get('roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])
            ->middleware('permission:roles.view')
            ->name('admin.roles.index');

        Route::get('roles/create', [\App\Http\Controllers\Admin\RoleController::class, 'create'])
            ->middleware('permission:roles.create')
            ->name('admin.roles.create');

        Route::post('roles', [\App\Http\Controllers\Admin\RoleController::class, 'store'])
            ->middleware('permission:roles.create')
            ->name('admin.roles.store');

        Route::get('roles/{role}/edit', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])
            ->middleware('permission:roles.edit')
            ->name('admin.roles.edit');

        Route::put('roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])
            ->middleware('permission:roles.edit')
            ->name('admin.roles.update');

        Route::delete('roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])
            ->middleware('permission:roles.delete')
            ->name('admin.roles.destroy');


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        Route::resource('users', UserController::class)
            ->only(['index'])
            ->middleware('permission:users.view')
            ->names('admin.users');

        Route::resource('users', UserController::class)
            ->only(['create', 'store'])
            ->middleware('permission:users.create')
            ->names('admin.users');

        Route::resource('users', UserController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:users.edit')
            ->names('admin.users');

        Route::resource('users', UserController::class)
            ->only(['destroy'])
            ->middleware('permission:users.delete')
            ->names('admin.users');


        /*
        |--------------------------------------------------------------------------
        | News
        |--------------------------------------------------------------------------
        */

        Route::resource('news', NewsController::class)
            ->only(['index'])
            ->middleware('permission:news.view')
            ->names('admin.news');

        Route::resource('news', NewsController::class)
            ->only(['create', 'store'])
            ->middleware('permission:news.create')
            ->names('admin.news');

        Route::resource('news', NewsController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:news.edit')
            ->names('admin.news');

        Route::resource('news', NewsController::class)
            ->only(['destroy'])
            ->middleware('permission:news.delete')
            ->names('admin.news');

        Route::delete('news-images/{image}', [NewsController::class, 'deleteImage'])
            ->middleware('permission:news.edit')
            ->name('admin.news-images.destroy');


        /*
        |--------------------------------------------------------------------------
        | Banners
        |--------------------------------------------------------------------------
        */

        Route::resource('banners', BannerController::class)
            ->only(['index'])
            ->middleware('permission:banners.view')
            ->names('admin.banners');

        Route::resource('banners', BannerController::class)
            ->only(['create', 'store'])
            ->middleware('permission:banners.create')
            ->names('admin.banners');

        Route::resource('banners', BannerController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:banners.edit')
            ->names('admin.banners');

        Route::resource('banners', BannerController::class)
            ->only(['destroy'])
            ->middleware('permission:banners.delete')
            ->names('admin.banners');


        /*
        |--------------------------------------------------------------------------
        | Settings - Footer
        |--------------------------------------------------------------------------
        */

        Route::get('settings/footer', [SettingController::class, 'editFooter'])
            ->middleware('permission:footer.view')
            ->name('admin.settings.footer');

        Route::post('settings/footer', [SettingController::class, 'updateFooter'])
            ->middleware('permission:footer.edit')
            ->name('admin.settings.footer.update');
    });


/*
|--------------------------------------------------------------------------
| ADMIN PANEL - BUSINESS MODULES
|--------------------------------------------------------------------------
| Combos, bookings, discount codes, destinations, attractions, blogs.
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['web', 'admin'])
    ->group(function () {

        /*
        ----------------------------------------------------------------
        | Combos
        ----------------------------------------------------------------
        */

        Route::resource('combos', ComboController::class)
            ->names('admin.combos');

        Route::patch('combos/{id}/toggle-status', [ComboController::class, 'toggleStatus'])
            ->name('admin.combos.toggle-status');

        Route::delete('combos/images/{image}', [ComboController::class, 'destroyImage'])
            ->name('admin.combos.images.destroy');

        Route::delete('combos/{id}/content-images/{index}', [ComboController::class, 'destroyContentImage'])
            ->name('admin.combos.content-images.destroy');


        /*
        |--------------------------------------------------------------------------
        | Combo Bookings
        |--------------------------------------------------------------------------
        */

        Route::get('combo-bookings', [\App\Http\Controllers\Admin\ComboBookingController::class, 'index'])
            ->name('admin.combo-bookings.index');

        Route::get('combo-bookings/{booking}', [\App\Http\Controllers\Admin\ComboBookingController::class, 'show'])
            ->name('admin.combo-bookings.show');

        Route::put('combo-bookings/{booking}/status', [\App\Http\Controllers\Admin\ComboBookingController::class, 'updateStatus'])
            ->name('admin.combo-bookings.update-status');

        Route::put('combo-bookings/{booking}/buyer-info', [\App\Http\Controllers\Admin\ComboBookingController::class, 'updateBuyerInfo'])
            ->name('admin.combo-bookings.update-buyer-info');

        Route::get('combo-bookings-export', [\App\Http\Controllers\Admin\ExcelExportController::class, 'exportComboBookings'])
            ->name('admin.combo-bookings.export');


        /*
        |--------------------------------------------------------------------------
        | Discount Codes
        |--------------------------------------------------------------------------
        */

        Route::resource('discount-codes', DiscountCodeController::class)
            ->names('admin.discount-codes');


        /*
        |--------------------------------------------------------------------------
        | Destinations
        |--------------------------------------------------------------------------
        */

        Route::resource('destinations', DestinationController::class)
            ->names('admin.destinations');


        /*
        |--------------------------------------------------------------------------
        | Attractions
        |--------------------------------------------------------------------------
        */

        Route::resource('attractions', AttractionController::class)
            ->names('admin.attractions');


        /*
        |----------------------------------------------------------------
        | Blogs - Admin
        ----------------------------------------------------------------
        */

        Route::resource('blogs', BlogController::class)
            ->names('admin.blogs');




        /*
        |----------------------------------------------------------------
        | liên hệ - Admin
        ----------------------------------------------------------------
        */

        Route::resource('contacts', App\Http\Controllers\Admin\ContactController::class)
            ->only(['index', 'show', 'destroy'])
            ->names('admin.contacts');

        /*
        |--------------------------------------------------------------------------
        | DISABLED / COMMENTED ADMIN FLIGHT MODULE
        |--------------------------------------------------------------------------
        | Giữ lại để sau này cần dùng thì gỡ comment.
        |--------------------------------------------------------------------------
        */

        // Route::resource('airlines', AdminAirlineController::class)->names('admin.airlines');
        // Route::patch('airlines/{id}/toggle-status', [AdminAirlineController::class, 'toggleStatus'])
        //     ->name('admin.airlines.toggle-status');

        // Route::resource('airports', AdminAirportController::class)->names('admin.airports');
        // Route::patch('airports/{id}/toggle-status', [AdminAirportController::class, 'toggleStatus'])
        //     ->name('admin.airports.toggle-status');

        // Route::resource('flights', AdminFlightController::class)->names('admin.flights');
        // Route::patch('flights/{id}/toggle-status', [AdminFlightController::class, 'toggleStatus'])
        //     ->name('admin.flights.toggle-status');

        // Route::get('flight-bookings', [\App\Http\Controllers\Admin\FlightBookingController::class, 'index'])
        //     ->name('admin.flight-bookings.index');

        // Route::get('flight-bookings/{booking}', [\App\Http\Controllers\Admin\FlightBookingController::class, 'show'])
        //     ->name('admin.flight-bookings.show');

        // Route::put('flight-bookings/{booking}/status', [\App\Http\Controllers\Admin\FlightBookingController::class, 'updateStatus'])
        //     ->name('admin.flight-bookings.update-status');

        // Route::put('flight-bookings/{booking}/buyer-info', [\App\Http\Controllers\Admin\FlightBookingController::class, 'updateBuyerInfo'])
        //     ->name('admin.flight-bookings.update-buyer-info');
    });


/*
|--------------------------------------------------------------------------
| FRONTEND - HOME
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/combo');


/*
|--------------------------------------------------------------------------
| FRONTEND - DESTINATIONS
|--------------------------------------------------------------------------
*/

Route::get('/diem-den-hot', [HomeController::class, 'destinations'])
    ->name('frontend.destinations');

Route::get('/diem-den/{slug}', [HomeController::class, 'showDestination'])
    ->name('frontend.destinations.show');

/*
|--------------------------------------------------------------------------
| Chatbot
|--------------------------------------------------------------------------
*/
// 1. Route hiển thị giao diện trang chat
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');

// 2. Route xử lý gửi tin nhắn bằng Ajax lên AI
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.send');


/*
|--------------------------------------------------------------------------
| Sepay
|--------------------------------------------------------------------------
*/
Route::post('/sepay/webhook', [SePayWebhookController::class, 'handleWebhook'])->name('sepay.webhook');

/*
|--------------------------------------------------------------------------
| FRONTEND - BLOGS / CẨM NANG
|--------------------------------------------------------------------------
*/

Route::get('cam-nang', [\App\Http\Controllers\BlogController::class, 'index'])
    ->name('blogs.index');

Route::get('cam-nang/{id}', [\App\Http\Controllers\BlogController::class, 'show'])
    ->name('blogs.show');


/*
|--------------------------------------------------------------------------
| FRONTEND - COMBO
|--------------------------------------------------------------------------
*/

Route::prefix('combo')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Combo Listing
    |--------------------------------------------------------------------------
    */

    Route::get('/', function (Request $request) {
        $keyword = trim((string) $request->query('keyword', ''));

        $combos = Combo::query()
            ->where('status', 1)
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($sub) use ($keyword) {
                    $sub->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('code', 'like', '%' . $keyword . '%')
                        ->orWhere('from_location', 'like', '%' . $keyword . '%')
                        ->orWhere('to_location', 'like', '%' . $keyword . '%');
                });
            })
            ->with([
                'departures' => function ($q) {
                    $q->where('status', 1)->orderBy('start_date', 'asc');
                },
                'departures.prices',
                'departures.sales',
            ])
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('combo.index', compact('combos', 'keyword'));
    })->name('combo.index');


    /*
    |--------------------------------------------------------------------------
    | Combo Passenger Form
    |--------------------------------------------------------------------------
    */

    Route::get('/hanh-khach', function (Request $request) {


        if (!session()->has('user')) {
            return redirect()->back()->withErrors(['auth' => 'Bạn cần đăng nhập để đặt combo.']);
        }

        $comboId = (int) $request->query('combo_id', 0);
        $slug = $request->query('slug');
        $departureId = (int) $request->query('departure_id', 0);
        $selectedStartDate = $request->query('selected_start_date');

        $adult = max(0, (int) $request->query('adult', 1));
        $child = max(0, (int) $request->query('child', 0));
        $infant = max(0, (int) $request->query('infant', 0));

        $combo = Combo::query()
            ->where('status', 1)
            ->when($comboId > 0, fn($q) => $q->where('id', $comboId))
            ->when(!empty($slug), fn($q) => $q->where('slug', $slug))
            ->with([
                'departures' => function ($q) use ($departureId) {
                    $q->where('status', 1);

                    if ($departureId > 0) {
                        $q->where('id', $departureId);
                    }

                    $q->orderBy('start_date', 'asc');
                },
                'departures.prices',
                'departures.sales',
                'discountCodes' => function ($q) {
                    $q->where('status', 1)->orderByDesc('id');
                },
            ])
            ->firstOrFail();

        $departure = $combo->departures->first();

        abort_if(!$departure, 404);

        $travelStartDate = $selectedStartDate
            ? Carbon::parse($selectedStartDate)
            : optional($departure->start_date)?->copy();

        abort_if(!$travelStartDate, 404);

        $travelEndDate = $travelStartDate->copy()->addDays(max(0, (int) $combo->duration_days - 1));

        if ($departure->start_date && $travelStartDate->lt($departure->start_date)) {
            abort(404);
        }

        if ($departure->end_date && $travelEndDate->gt($departure->end_date)) {
            abort(404);
        }

        $saleDate = $travelStartDate->format('Y-m-d');
        $salePercent = (int) $departure->getSalePercentForDate($saleDate);

        $adultBasePrice = (int) ($departure->prices->firstWhere('passenger_type', 'adult')?->base_price ?? 0);
        $childBasePrice = (int) ($departure->prices->firstWhere('passenger_type', 'child')?->base_price ?? 0);
        $infantBasePrice = (int) ($departure->prices->firstWhere('passenger_type', 'infant')?->base_price ?? 0);

        $adultFinalPrice = $adultBasePrice > 0 ? (int) round($adultBasePrice * (100 - $salePercent) / 100) : 0;
        $childFinalPrice = $childBasePrice > 0 ? (int) round($childBasePrice * (100 - $salePercent) / 100) : 0;
        $infantFinalPrice = $infantBasePrice > 0 ? (int) round($infantBasePrice * (100 - $salePercent) / 100) : 0;

        $totalPassengers = $adult + $child + $infant;

        $totalAmount =
            ($adult * $adultFinalPrice) +
            ($child * $childFinalPrice) +
            ($infant * $infantFinalPrice);

        $displayDiscountCode = $combo->discountCodes->first();

        $appliedDiscountCode = null;
        $couponDiscountAmount = 0;
        $payableAmount = $totalAmount;

        return view('combo.booking.passenger', compact(
            'combo',
            'departure',
            'travelStartDate',
            'travelEndDate',
            'adult',
            'child',
            'infant',
            'salePercent',
            'adultBasePrice',
            'childBasePrice',
            'infantBasePrice',
            'adultFinalPrice',
            'childFinalPrice',
            'infantFinalPrice',
            'totalPassengers',
            'totalAmount',
            'displayDiscountCode',
            'appliedDiscountCode',
            'couponDiscountAmount',
            'payableAmount'
        ));
    })->name('combo.passenger');


    /*
    |--------------------------------------------------------------------------
    | Combo Discount Code
    |--------------------------------------------------------------------------
    */

    Route::post('/kiem-tra-ma-giam-gia', function (Request $request) {
        $validated = $request->validate([
            'combo_id' => ['required', 'integer', 'exists:combos,id'],
            'departure_id' => ['required', 'integer', 'exists:combo_departures,id'],
            'travel_start_date' => ['required', 'date'],
            'adult' => ['required', 'integer', 'min:0'],
            'child' => ['nullable', 'integer', 'min:0'],
            'infant' => ['nullable', 'integer', 'min:0'],
            'coupon_code' => ['required', 'string', 'max:50'],
        ]);

        $combo = Combo::query()
            ->where('status', 1)
            ->where('id', (int) $validated['combo_id'])
            ->with([
                'departures' => function ($q) use ($validated) {
                    $q->where('status', 1)
                        ->where('id', (int) $validated['departure_id']);
                },
                'departures.prices',
                'departures.sales',
            ])
            ->first();

        if (!$combo || !$combo->departures->first()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy combo hoặc đợt khởi hành hợp lệ.',
            ], 422);
        }

        $departure = $combo->departures->first();
        $travelStartDate = Carbon::parse($validated['travel_start_date']);

        $saleDate = $travelStartDate->format('Y-m-d');
        $salePercent = (int) $departure->getSalePercentForDate($saleDate);

        $adult = max(0, (int) $validated['adult']);
        $child = max(0, (int) ($validated['child'] ?? 0));
        $infant = max(0, (int) ($validated['infant'] ?? 0));

        $adultBasePrice = (int) ($departure->prices->firstWhere('passenger_type', 'adult')?->base_price ?? 0);
        $childBasePrice = (int) ($departure->prices->firstWhere('passenger_type', 'child')?->base_price ?? 0);
        $infantBasePrice = (int) ($departure->prices->firstWhere('passenger_type', 'infant')?->base_price ?? 0);

        $adultFinalPrice = $adultBasePrice > 0 ? (int) round($adultBasePrice * (100 - $salePercent) / 100) : 0;
        $childFinalPrice = $childBasePrice > 0 ? (int) round($childBasePrice * (100 - $salePercent) / 100) : 0;
        $infantFinalPrice = $infantBasePrice > 0 ? (int) round($infantBasePrice * (100 - $salePercent) / 100) : 0;

        $totalAmount =
            ($adult * $adultFinalPrice) +
            ($child * $childFinalPrice) +
            ($infant * $infantFinalPrice);

        $couponCode = strtoupper(trim($validated['coupon_code']));

        $discountCode = DiscountCode::query()
            ->where('combo_id', $combo->id)
            ->where('code', $couponCode)
            ->where('status', 1)
            ->first();

        if (!$discountCode) {
            return response()->json([
                'success' => false,
                'message' => 'Mã đã sai, vui lòng thực hiện lại.',
            ], 422);
        }

        if (!$discountCode->isApplicableForTravelDate($travelStartDate)) {
            return response()->json([
                'success' => false,
                'message' => $discountCode->isExpired()
                    ? 'Mã giảm giá đã hết hiệu lực.'
                    : 'Mã giảm giá không áp dụng cho ngày check-in đã chọn.',
            ], 422);
        }

        $discountAmount = $discountCode->calculateDiscountAmount($totalAmount);
        $payableAmount = max(0, $totalAmount - $discountAmount);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công.',
            'discount_code_id' => $discountCode->id,
            'discount_code' => $discountCode->code,
            'discount_percent' => $discountCode->discount_percent,
            'discount_amount' => $discountAmount,
            'payable_amount' => $payableAmount,
        ]);
    })->name('combo.discount-code.validate');


    /*
    |--------------------------------------------------------------------------
    | Combo Passenger Store
    |--------------------------------------------------------------------------
    */

    Route::post('/hanh-khach', function (Request $request) {

        if (!session()->has('user')) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập.'], 401);
        }

        $validated = $request->validate([
            'combo_id' => ['required', 'integer', 'exists:combos,id'],
            'departure_id' => ['required', 'integer', 'exists:combo_departures,id'],
            'travel_start_date' => ['required', 'date'],
            'travel_end_date' => ['required', 'date', 'after_or_equal:travel_start_date'],

            'adult' => ['required', 'integer', 'min:0'],
            'child' => ['nullable', 'integer', 'min:0'],
            'infant' => ['nullable', 'integer', 'min:0'],

            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],

            'invoice_required' => ['nullable', 'in:1'],
            'invoice_tax' => ['nullable', 'string', 'max:255'],
            'invoice_company' => ['nullable', 'string', 'max:255'],
            'invoice_address' => ['nullable', 'string', 'max:255'],
            'invoice_email' => ['nullable', 'email', 'max:255'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ]);

        $combo = Combo::query()
            ->where('status', 1)
            ->where('id', (int) $validated['combo_id'])
            ->with([
                'departures' => function ($q) use ($validated) {
                    $q->where('status', 1)
                        ->where('id', (int) $validated['departure_id']);
                },
                'departures.prices',
                'departures.sales',
            ])
            ->firstOrFail();

        $departure = $combo->departures->first();

        abort_if(!$departure, 404);

        $travelStartDate = Carbon::parse($validated['travel_start_date']);
        $travelEndDate = Carbon::parse($validated['travel_end_date']);

        $expectedEndDate = $travelStartDate->copy()->addDays(max(0, (int) $combo->duration_days - 1));

        if (!$travelEndDate->isSameDay($expectedEndDate)) {
            return back()->withErrors([
                'travel_end_date' => 'Ngày kết thúc không hợp lệ theo lịch trình combo.',
            ])->withInput();
        }

        if ($departure->start_date && $travelStartDate->lt($departure->start_date)) {
            return back()->withErrors([
                'travel_start_date' => 'Ngày bắt đầu phải nằm trong khoảng khởi hành.',
            ])->withInput();
        }

        if ($departure->end_date && $travelEndDate->gt($departure->end_date)) {
            return back()->withErrors([
                'travel_end_date' => 'Ngày kết thúc vượt quá khoảng khởi hành cho phép.',
            ])->withInput();
        }

        $adult = max(0, (int) ($validated['adult'] ?? 1));
        $child = max(0, (int) ($validated['child'] ?? 0));
        $infant = max(0, (int) ($validated['infant'] ?? 0));

        if (($adult + $child + $infant) <= 0) {
            return back()->withErrors([
                'adult' => 'Phải có ít nhất 1 hành khách.',
            ])->withInput();
        }

        $saleDate = $travelStartDate->format('Y-m-d');
        $salePercent = (int) $departure->getSalePercentForDate($saleDate);

        $adultBasePrice = (int) ($departure->prices->firstWhere('passenger_type', 'adult')?->base_price ?? 0);
        $childBasePrice = (int) ($departure->prices->firstWhere('passenger_type', 'child')?->base_price ?? 0);
        $infantBasePrice = (int) ($departure->prices->firstWhere('passenger_type', 'infant')?->base_price ?? 0);

        $adultFinalPrice = $adultBasePrice > 0 ? (int) round($adultBasePrice * (100 - $salePercent) / 100) : 0;
        $childFinalPrice = $childBasePrice > 0 ? (int) round($childBasePrice * (100 - $salePercent) / 100) : 0;
        $infantFinalPrice = $infantBasePrice > 0 ? (int) round($infantBasePrice * (100 - $salePercent) / 100) : 0;

        $totalPassengers = $adult + $child + $infant;

        $totalAmount =
            ($adult * $adultFinalPrice) +
            ($child * $childFinalPrice) +
            ($infant * $infantFinalPrice);

        $discountCode = null;
        $discountCodePercent = 0;
        $discountCodeAmount = 0;
        $payableAmount = $totalAmount;

        $couponCode = strtoupper(trim((string) $request->input('coupon_code', '')));

        if ($couponCode !== '') {
            $discountCode = DiscountCode::query()
                ->where('combo_id', $combo->id)
                ->where('code', $couponCode)
                ->where('status', 1)
                ->first();

            if (!$discountCode) {
                return back()->withErrors([
                    'coupon_code' => 'Mã đã sai, vui lòng thực hiện lại.',
                ])->withInput();
            }

            if (!$discountCode->isApplicableForTravelDate($travelStartDate)) {
                return back()->withErrors([
                    'coupon_code' => $discountCode->isExpired()
                        ? 'Mã giảm giá đã hết hiệu lực.'
                        : 'Mã giảm giá không áp dụng cho ngày check-in đã chọn.',
                ])->withInput();
            }

            $discountCodePercent = (int) $discountCode->discount_percent;
            $discountCodeAmount = $discountCode->calculateDiscountAmount($totalAmount);
            $payableAmount = max(0, $totalAmount - $discountCodeAmount);
        }

        $invoiceRequired = $request->boolean('invoice_required');

        $booking = ComboBooking::create([
            'combo_id' => $combo->id,
            'departure_id' => $departure->id,
            'discount_code_id' => $discountCode?->id,
            'discount_code' => $discountCode?->code,
            'travel_start_date' => $travelStartDate->format('Y-m-d'),
            'travel_end_date' => $travelEndDate->format('Y-m-d'),

            'adult' => $adult,
            'child' => $child,
            'infant' => $infant,
            'total_passengers' => $totalPassengers,

            'adult_final_price' => $adultFinalPrice,
            'child_final_price' => $childFinalPrice,
            'infant_final_price' => $infantFinalPrice,
            'sale_percent' => $salePercent,
            'discount_code_percent' => $discountCodePercent,
            'discount_code_amount' => $discountCodeAmount,
            'final_amount' => $payableAmount,
            'total_amount' => $totalAmount,

            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'] ?? null,

            'invoice_required' => $invoiceRequired,
            'invoice_tax' => $invoiceRequired ? ($validated['invoice_tax'] ?? null) : null,
            'invoice_company' => $invoiceRequired ? ($validated['invoice_company'] ?? null) : null,
            'invoice_address' => $invoiceRequired ? ($validated['invoice_address'] ?? null) : null,
            'invoice_email' => $invoiceRequired ? ($validated['invoice_email'] ?? null) : null,

            'payment_status' => 'pending',
            'booking_status' => 'draft',

            'payment_expired_at' => now()->addMinutes(90),
        ]);

        $booking->update([
            'booking_code' => 'CB' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT),
        ]);

        \App\Models\ComboBookingHistory::create([
            'combo_booking_id' => $booking->id,
            'action' => 'created',
            'field_name' => null,
            'old_value' => null,
            'new_value' => null,
            'changed_by_type' => 'customer',
            'changed_by_id' => null,
            'changed_by_name' => $booking->contact_name,
            'note' => 'Khách hàng tạo đơn booking ban đầu.',
        ]);

        return redirect()->route('combo.payment', [
            'booking_id' => $booking->id,
        ]);
    })->name('combo.passenger.store');


    /*
    |--------------------------------------------------------------------------
    | Combo Payment
    |--------------------------------------------------------------------------
    */

    Route::get('/thanh-toan', function (Request $request) {
        $bookingId = (int) $request->query('booking_id', 0);

        $booking = ComboBooking::query()
            ->with([
                'combo',
                'departure',
            ])
            ->findOrFail($bookingId);

        if (
            $booking->payment_status !== 'paid' &&
            $booking->payment_expired_at &&
            now()->greaterThan($booking->payment_expired_at)
        ) {
            $booking->update([
                'payment_status' => 'expired',
                'booking_status' => 'expired',
            ]);

            $booking->refresh();
        }

        return view('combo.booking.payment', compact('booking'));
    })->name('combo.payment');

    Route::post('/thanh-toan/cap-nhat-thong-tin', function (Request $request) {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:combo_bookings,id'],

            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],

            'invoice_required' => ['nullable', 'in:1'],
            'invoice_tax' => ['nullable', 'string', 'max:255'],
            'invoice_company' => ['nullable', 'string', 'max:255'],
            'invoice_address' => ['nullable', 'string', 'max:255'],
            'invoice_email' => ['nullable', 'email', 'max:255'],
        ]);

        $booking = ComboBooking::query()->findOrFail((int) $validated['booking_id']);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('combo.payment', [
                'booking_id' => $booking->id,
            ])->withErrors([
                'payment' => 'Đơn hàng đã thanh toán, không thể thay đổi thông tin người mua.',
            ]);
        }

        if (
            $booking->payment_status === 'expired' ||
            ($booking->payment_expired_at && now()->greaterThan($booking->payment_expired_at))
        ) {
            return redirect()->route('combo.payment', [
                'booking_id' => $booking->id,
            ])->withErrors([
                'payment' => 'Đơn hàng đã hết thời gian thanh toán, không thể thay đổi thông tin người mua.',
            ]);
        }

        $invoiceRequired = $request->boolean('invoice_required');

        $newData = [
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'] ?? null,

            'invoice_required' => $invoiceRequired,
            'invoice_tax' => $invoiceRequired ? ($validated['invoice_tax'] ?? null) : null,
            'invoice_company' => $invoiceRequired ? ($validated['invoice_company'] ?? null) : null,
            'invoice_address' => $invoiceRequired ? ($validated['invoice_address'] ?? null) : null,
            'invoice_email' => $invoiceRequired ? ($validated['invoice_email'] ?? null) : null,
        ];

        $fields = [
            'contact_name' => 'Họ và tên',
            'contact_phone' => 'Số điện thoại',
            'contact_email' => 'Email',
            'invoice_required' => 'Yêu cầu xuất hóa đơn',
            'invoice_tax' => 'Mã số thuế',
            'invoice_company' => 'Tên công ty',
            'invoice_address' => 'Địa chỉ công ty',
            'invoice_email' => 'Email hóa đơn',
        ];

        $oldData = $booking->only(array_keys($fields));

        $booking->update($newData);

        foreach ($fields as $field => $label) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $newData[$field] ?? null;

            if ((string) $oldValue !== (string) $newValue) {
                \App\Models\ComboBookingHistory::create([
                    'combo_booking_id' => $booking->id,
                    'action' => 'updated_contact_info',
                    'field_name' => $field,
                    'old_value' => is_bool($oldValue) ? ($oldValue ? '1' : '0') : (is_null($oldValue) ? null : (string) $oldValue),
                    'new_value' => is_bool($newValue) ? ($newValue ? '1' : '0') : (is_null($newValue) ? null : (string) $newValue),
                    'changed_by_type' => 'customer',
                    'changed_by_id' => null,
                    'changed_by_name' => $booking->contact_name,
                    'note' => 'Khách hàng cập nhật thông tin người mua: ' . $label,
                ]);
            }
        }

        return redirect()->route('combo.payment', [
            'booking_id' => $booking->id,
        ])->with('success', 'Cập nhật thông tin người mua thành công.');
    })->name('combo.payment.update-contact');

    Route::post('/thanh-toan', function (Request $request) {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:combo_bookings,id'],
            'payment_method' => ['required', 'in:atm,international,vnpt'],
        ]);

        $booking = ComboBooking::query()->findOrFail((int) $validated['booking_id']);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('combo.payment', [
                'booking_id' => $booking->id,
            ])->with('success', 'Đơn hàng này đã thanh toán.');
        }

        if (
            $booking->payment_status === 'expired' ||
            ($booking->payment_expired_at && now()->greaterThan($booking->payment_expired_at))
        ) {
            return back()->withErrors([
                'payment_method' => 'Đơn hàng đã hết thời gian thanh toán.',
            ]);
        }

        $booking->update([
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'booking_status' => 'pending_payment',
        ]);

        return redirect()->route('combo.payment', [
            'booking_id' => $booking->id,
        ])->with('success', 'Đã ghi nhận phương thức thanh toán.');
    })->name('combo.payment.store');

    Route::post('/thanh-toan/hoan-tat', function (Request $request) {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:combo_bookings,id'],
        ]);

        $booking = ComboBooking::query()->findOrFail((int) $validated['booking_id']);

        if (
            $booking->payment_status === 'expired' ||
            ($booking->payment_expired_at && now()->greaterThan($booking->payment_expired_at))
        ) {
            return back()->withErrors([
                'payment' => 'Đơn hàng đã hết thời gian thanh toán.',
            ]);
        }

        $booking->update([
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
            'paid_at' => now(),
        ]);

        return redirect()->route('combo.index')
            ->with('success', 'Thanh toán thành công.');
    })->name('combo.payment.complete');


    /*
    |--------------------------------------------------------------------------
    | Combo Detail
    |--------------------------------------------------------------------------
    */

    Route::get('/{slug}', function (Request $request, $slug) {
        $combo = Combo::query()
            ->where('status', 1)
            ->where('slug', $slug)
            ->with([
                'images',
                'departures' => function ($q) {
                    $q->where('status', 1)->orderBy('start_date', 'asc');
                },
                'departures.prices',
                'departures.sales',
                'discountCodes' => function ($q) {
                    $q->where('status', 1)->orderByDesc('id');
                },
            ])
            ->firstOrFail();

        $selectedDepartureId = (int) $request->query('departure', 0);

        return view('combo.show', compact('combo', 'selectedDepartureId'));
    })->name('combo.show');
});

/*
|-----------------------------------------------------------------------
| FRONTEND - liên hệ
|-----------------------------------------------------------------------
*/

// Trang hiển thị form liên hệ
Route::get('lien-he', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');

// Xử lý khi khách nhấn nút "Gửi tin nhắn"
Route::post('lien-he', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| DISABLED / COMMENTED FRONTEND FLIGHT MODULE
|--------------------------------------------------------------------------
| Giữ lại để sau này cần dùng thì gỡ comment.
|--------------------------------------------------------------------------
*/

// Route::get('/flightsearch', [FlightController::class, 'search'])->name('flight.search');

// Route::get('/flight/booking', function (Request $request) {
//     $flightId = (int) $request->query('flight_id', 0);

//     $flight = \App\Models\Flight::query()
//         ->with(['airline', 'departureAirport', 'arrivalAirport'])
//         ->where('is_active', true)
//         ->findOrFail($flightId);

//     $adult = max(1, (int) $request->query('adult', 1));
//     $child = max(0, (int) $request->query('child', 0));
//     $infant = max(0, (int) $request->query('infant', 0));

//     $totalPassengers = $adult + $child + $infant;

//     $adultPrice = (int) $flight->adult_price;
//     $childPrice = (int) $flight->child_price;
//     $infantPrice = (int) $flight->infant_price;
//     $taxFee = (int) $flight->tax_fee;

//     $totalAmount =
//         ($adult * $adultPrice) +
//         ($child * $childPrice) +
//         ($infant * $infantPrice) +
//         $taxFee;

//     $finalAmount = $totalAmount;

//     return view('flight-booking', compact(
//         'flight',
//         'adult',
//         'child',
//         'infant',
//         'totalPassengers',
//         'adultPrice',
//         'childPrice',
//         'infantPrice',
//         'taxFee',
//         'totalAmount',
//         'finalAmount'
//     ));
// })->name('flight.booking');

// Route::post('/flight/booking', function (Request $request) {
//     $validated = $request->validate([
//         'flight_id' => ['required', 'integer', 'exists:flights,id'],
//         'adult' => ['required', 'integer', 'min:1'],
//         'child' => ['nullable', 'integer', 'min:0'],
//         'infant' => ['nullable', 'integer', 'min:0'],

//         'contact_name' => ['required', 'string', 'max:255'],
//         'contact_phone' => ['required', 'string', 'max:50'],
//         'contact_email' => ['nullable', 'email', 'max:255'],
//     ]);

//     $flight = \App\Models\Flight::query()
//         ->where('is_active', true)
//         ->findOrFail((int) $validated['flight_id']);

//     $adult = max(1, (int) ($validated['adult'] ?? 1));
//     $child = max(0, (int) ($validated['child'] ?? 0));
//     $infant = max(0, (int) ($validated['infant'] ?? 0));
//     $totalPassengers = $adult + $child + $infant;

//     if ($flight->available_seats < $totalPassengers) {
//         return back()->withErrors([
//             'adult' => 'Số ghế còn lại không đủ cho số hành khách đã chọn.',
//         ])->withInput();
//     }

//     $adultPrice = (int) $flight->adult_price;
//     $childPrice = (int) $flight->child_price;
//     $infantPrice = (int) $flight->infant_price;
//     $taxFee = (int) $flight->tax_fee;

//     $totalAmount =
//         ($adult * $adultPrice) +
//         ($child * $childPrice) +
//         ($infant * $infantPrice) +
//         $taxFee;

//     $booking = FlightBooking::create([
//         'flight_id' => $flight->id,

//         'adult' => $adult,
//         'child' => $child,
//         'infant' => $infant,
//         'total_passengers' => $totalPassengers,

//         'adult_price' => $adultPrice,
//         'child_price' => $childPrice,
//         'infant_price' => $infantPrice,
//         'tax_fee' => $taxFee,
//         'total_amount' => $totalAmount,
//         'final_amount' => $totalAmount,

//         'contact_name' => $validated['contact_name'],
//         'contact_phone' => $validated['contact_phone'],
//         'contact_email' => $validated['contact_email'] ?? null,

//         'payment_status' => 'pending',
//         'booking_status' => 'pending_payment',
//         'payment_expired_at' => now()->addMinutes(90),
//     ]);

//     $booking->update([
//         'booking_code' => 'FL' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT),
//     ]);

//     return redirect()->route('flight.booking.success', ['booking_id' => $booking->id]);
// })->name('flight.booking.store');

// Route::get('/flight/booking/success', function (Request $request) {
//     $bookingId = (int) $request->query('booking_id', 0);

//     $booking = FlightBooking::query()
//         ->with(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport'])
//         ->findOrFail($bookingId);

//     return view('flight-booking-success', compact('booking'));
// })->name('flight.booking.success');