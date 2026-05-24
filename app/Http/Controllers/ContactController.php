<?php
namespace App\Http\Controllers;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller {
    public function index() {
        return view('contact.index');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'subject' => 'nullable|string',
            'message' => 'required|string',
        ]);

        Contact::create($data);
        return back()->with('success', 'Cảm ơn bạn! Tin nhắn của bạn đã được gửi đi thành công.');
    }
}