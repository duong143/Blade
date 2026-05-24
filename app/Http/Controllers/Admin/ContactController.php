<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ContactService;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $service;

    public function __construct(ContactService $service)
    {
        $this->service = $service;
    }

    // Hiển thị danh sách
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ thanh URL (?keyword=...)
        $keyword = trim((string) $request->query('keyword', ''));

        // Query danh sách liên hệ kết hợp bộ lọc thông minh
        $contacts = Contact::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%')
                        ->orWhere('phone', 'like', '%' . $keyword . '%')
                        ->orWhere('subject', 'like', '%' . $keyword . '%'); // Tìm cả tiêu đề nếu có
                });
            })
            ->latest() 
            ->paginate(10) 
            ->withQueryString(); 

        return view('admin.contacts.index', compact('contacts', 'keyword'));
    }

    // Hiển thị chi tiết
    public function show($id)
    {
        $contact = $this->service->getContactDetail($id);
        return view('admin.contacts.show', compact('contact'));
    }

    // Xóa
    public function destroy($id)
    {
        $this->service->deleteContact($id);
        return back()->with('success', 'Đã xóa tin nhắn liên hệ thành công!');
    }
}
