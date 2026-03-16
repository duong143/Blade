<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Models\Combo;
use App\Models\ComboBooking;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\SettingController;

use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\ComboDepartureController;
use App\Http\Controllers\Admin\ComboDeparturePriceController;
use App\Http\Controllers\Admin\ComboDepartureSaleController;


/*
|--------------------------------------------------------------------------
| AUTH (USER – FRONTEND)
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
| ADMIN AUTH (KHÔNG DÙNG middleware admin)
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
| ADMIN PANEL (SESSION ADMIN + SPATIE)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['admin'])
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])
            ->name('admin.dashboard');

        // ROLES
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

        // USERS
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

        // NEWS
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
            ->name('admin.news-images.destroy')
            ->middleware('permission:news.edit');

        // BANNERS
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

        // SETTINGS - FOOTER
        Route::get('settings/footer', [SettingController::class, 'editFooter'])
            ->middleware('permission:footer.view')
            ->name('admin.settings.footer');

        Route::post('settings/footer', [SettingController::class, 'updateFooter'])
            ->middleware('permission:footer.edit')
            ->name('admin.settings.footer.update');
    });

/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/flightsearch', function () {
    return view('flightsearch');
});

/*
|--------------------------------------------------------------------------
| COMBO FRONTEND
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| COMBO FRONTEND
|--------------------------------------------------------------------------
*/
Route::prefix('combo')->group(function () {

    Route::get('/', function () {

        $combos = Combo::query()
            ->where('status', 1)
            ->with([
                'departures' => function ($q) {
                    $q->where('status', 1)->orderBy('start_date', 'asc');
                },
                'departures.prices',
                'departures.sales',
            ])
            ->latest()
            ->paginate(9);

        return view('combo.index', compact('combos'));
    })->name('combo.index');

    Route::get('/hanh-khach', function (Request $request) {
        $comboId = (int) $request->query('combo_id', 0);
        $slug = $request->query('slug');
        $departureId = (int) $request->query('departure_id', 0);

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
            ])
            ->firstOrFail();

        $departure = $combo->departures->first();

        abort_if(!$departure, 404);

        $saleDate = optional($departure->start_date)->format('Y-m-d') ?: now()->toDateString();
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

        return view('combo.booking.passenger', compact(
            'combo',
            'departure',
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
            'totalAmount'
        ));
    })->name('combo.passenger');

    Route::post('/hanh-khach', function (Request $request) {
        $validated = $request->validate([
            'combo_id' => ['required', 'integer', 'exists:combos,id'],
            'departure_id' => ['required', 'integer', 'exists:combo_departures,id'],

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

        $adult = max(0, (int) ($validated['adult'] ?? 1));
        $child = max(0, (int) ($validated['child'] ?? 0));
        $infant = max(0, (int) ($validated['infant'] ?? 0));

        if (($adult + $child + $infant) <= 0) {
            return back()->withErrors([
                'adult' => 'Phải có ít nhất 1 hành khách.',
            ])->withInput();
        }

        $saleDate = optional($departure->start_date)->format('Y-m-d') ?: now()->toDateString();
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

        $invoiceRequired = $request->boolean('invoice_required');

        $booking = ComboBooking::create([
            'combo_id' => $combo->id,
            'departure_id' => $departure->id,

            'adult' => $adult,
            'child' => $child,
            'infant' => $infant,
            'total_passengers' => $totalPassengers,

            'adult_final_price' => $adultFinalPrice,
            'child_final_price' => $childFinalPrice,
            'infant_final_price' => $infantFinalPrice,
            'sale_percent' => $salePercent,
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

        return redirect()->route('combo.payment', [
            'booking_id' => $booking->id,
        ]);
    })->name('combo.passenger.store');

    Route::get('/thanh-toan', function (Request $request) {
        $bookingId = (int) $request->query('booking_id', 0);

        $booking = ComboBooking::query()
            ->with([
                'combo',
                'departure',
            ])
            ->findOrFail($bookingId);

        if ($booking->payment_expired_at && now()->greaterThan($booking->payment_expired_at)) {
            $booking->update([
                'payment_status' => 'expired',
                'booking_status' => 'expired',
            ]);
        }

        return view('combo.booking.payment', compact('booking'));
    })->name('combo.payment');

    Route::post('/thanh-toan', function (Request $request) {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:combo_bookings,id'],
            'payment_method' => ['required', 'in:atm,international,vnpt'],
        ]);

        $booking = ComboBooking::query()->findOrFail((int) $validated['booking_id']);

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
            ])
            ->firstOrFail();

        $selectedDepartureId = (int) $request->query('departure', 0);

        return view('combo.show', compact('combo', 'selectedDepartureId'));
    })->name('combo.show');
});

/*
|--------------------------------------------------------------------------
| COMBO ADMIN (combos + departures + prices + sales)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['web', 'admin'])->group(function () {

    Route::resource('combos', ComboController::class)->names('admin.combos');
    Route::delete('combos/images/{image}', [ComboController::class, 'destroyImage'])
        ->name('admin.combos.images.destroy');
    Route::resource('combo-departures', ComboDepartureController::class)
        ->names('admin.combo-departures');

    // PRICES
    Route::post('combo-departure-prices', [ComboDeparturePriceController::class, 'store'])
        ->name('admin.combo-departure-prices.store');

    Route::delete('combo-departure-prices/{combo_departure_price}', [ComboDeparturePriceController::class, 'destroy'])
        ->name('admin.combo-departure-prices.destroy');

    // SALES
    Route::post('combo-departure-sales', [ComboDepartureSaleController::class, 'store'])
        ->name('admin.combo-departure-sales.store');

    Route::delete('combo-departure-sales/{combo_departure_sale}', [ComboDepartureSaleController::class, 'destroy'])
        ->name('admin.combo-departure-sales.destroy');
});
