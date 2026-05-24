<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDiscountCodeRequest;
use App\Http\Requests\Admin\UpdateDiscountCodeRequest;
use App\Models\DiscountCode;
use App\Services\Admin\DiscountCodeService;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function __construct(
        protected DiscountCodeService $discountCodeService
    ) {}

    public function index(Request $request)
    {
        $discountCodes = $this->discountCodeService->getPaginatedDiscountCodes($request);

        return view('admin.discount-codes.index', compact('discountCodes'));
    }

    public function create()
    {
        $combos = $this->discountCodeService->getAllCombos();

        return view('admin.discount-codes.create', compact('combos'));
    }

    public function store(StoreDiscountCodeRequest $request)
    {
        $this->discountCodeService->createDiscountCode($request->validated());

        return redirect()
            ->route('admin.discount-codes.index')
            ->with('success', 'Tạo mã giảm giá thành công');
    }

    public function edit($id)
    {
        $discountCode = $this->discountCodeService->getDiscountCodeById((int) $id);
        $combos = $this->discountCodeService->getAllCombos();

        return view('admin.discount-codes.edit', compact('discountCode', 'combos'));
    }

    public function update(UpdateDiscountCodeRequest $request, $id)
    {
        $discountCode = DiscountCode::findOrFail($id);

        $this->discountCodeService->updateDiscountCode($discountCode, $request->validated());

        return redirect()
            ->route('admin.discount-codes.index')
            ->with('success', 'Cập nhật mã giảm giá thành công');
    }

    public function destroy($id)
    {
        $this->discountCodeService->deleteDiscountCode((int) $id);

        return redirect()
            ->route('admin.discount-codes.index')
            ->with('success', 'Xóa mã giảm giá thành công');
    }
}
