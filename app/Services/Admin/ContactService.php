<?php

namespace App\Services\Admin;

use App\Models\Contact;

class ContactService
{
    /**
     * Lấy danh sách liên hệ phân trang
     * Hàm này đang bị thiếu dẫn đến lỗi của bạn
     */
    public function getPaginatedContacts($perPage = 10)
    {
        return Contact::latest()->paginate($perPage);
    }

    /**
     * Lấy chi tiết và đánh dấu đã đọc
     */
    public function getContactDetail($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => true]);
        return $contact;
    }

    /**
     * Xóa liên hệ
     */
    public function deleteContact($id)
    {
        $contact = Contact::findOrFail($id);
        return $contact->delete();
    }
}
