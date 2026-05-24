<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreComboRequest;
use App\Http\Requests\Admin\UpdateComboRequest;
use App\Models\Combo;
use App\Services\Admin\ComboService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ComboController extends Controller
{
    public function __construct(
        protected ComboService $comboService
    ) {}

    public function index(Request $request)
    {
        $combos = $this->comboService->getPaginatedCombos($request);

        return view('admin.combos.index', compact('combos'));
    }

    public function create()
    {
        return view('admin.combos.create');
    }

    public function store(StoreComboRequest $request)
    {
        $this->comboService->createCombo($request->validated(), $request);

        return redirect()
            ->route('admin.combos.index')
            ->with('success', 'Tạo combo thành công');
    }

    public function edit($id)
    {
        $combo = $this->comboService->getComboForEdit((int) $id);

        return view('admin.combos.edit', compact('combo'));
    }

    public function update(UpdateComboRequest $request, $id)
    {
        $combo = Combo::findOrFail($id);

        $this->comboService->updateCombo($combo, $request->validated(), $request);

        return redirect()
            ->route('admin.combos.index')
            ->with('success', 'Cập nhật combo thành công');
    }

    public function destroy($id)
    {
        $this->comboService->deleteCombo((int) $id);

        return redirect()
            ->route('admin.combos.index')
            ->with('success', 'Xóa combo thành công');
    }

    public function destroyImage($id)
    {
        $this->comboService->deleteImage((int) $id);

        return back()->with('success', 'Xóa ảnh banner thành công');
    }

    public function destroyContentImage($id, $index)
    {
        try {
            $this->comboService->deleteContentImage((int) $id, (int) $index);

            return back()->with('success', 'Xóa ảnh nội dung chi tiết thành công');
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }
    }

    public function toggleStatus($id)
    {
        $result = $this->comboService->toggleStatus((int) $id);

        return response()->json($result);
    }
}
