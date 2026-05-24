<?php

namespace App\Services\Admin;

use App\Models\Combo;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeService
{
    public function getPaginatedDiscountCodes(Request $request)
    {
        $query = DiscountCode::query()->with('combo');

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('code', 'like', '%' . $keyword . '%')
                    ->orWhereHas('combo', function ($sub) use ($keyword) {
                        $sub->where('title', 'like', '%' . $keyword . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->status);
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function getAllCombos()
    {
        return Combo::orderBy('title')->get();
    }

    public function getDiscountCodeById(int $id): DiscountCode
    {
        return DiscountCode::findOrFail($id);
    }

    public function createDiscountCode(array $data): DiscountCode
    {
        $data['code'] = strtoupper(trim($data['code']));

        return DiscountCode::create($data);
    }

    public function updateDiscountCode(DiscountCode $discountCode, array $data): DiscountCode
    {
        $data['code'] = strtoupper(trim($data['code']));

        $discountCode->update($data);

        return $discountCode;
    }

    public function deleteDiscountCode(int $id): void
    {
        $discountCode = DiscountCode::findOrFail($id);
        $discountCode->delete();
    }
}
