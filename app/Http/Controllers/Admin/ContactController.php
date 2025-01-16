<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact; // Mô hình liên hệ
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Hiển thị danh sách liên hệ
    public function index()
    {
        $contacts = Contact::all(); // Hoặc có thể paginate nếu cần
        return view('admin.contacts.listContact', compact('contacts'));
    }

    // Xem chi tiết liên hệ
    public function show(Contact $contact)
    {
        return view('admin.contacts.detailContact', compact('contact'));
    }
}

