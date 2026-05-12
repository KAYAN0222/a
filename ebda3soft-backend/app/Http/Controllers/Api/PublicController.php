<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    // نموذج التواصل
    public function contact(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'email'     => 'nullable|email',
            'phone'     => 'nullable|string|max:30',
            'subject'   => 'nullable|string|max:200',
            'message'   => 'required|string|max:2000',
        ]);

        ContactMessage::create($data);
        return response()->json(['message' => 'تم إرسال رسالتك بنجاح، سنتواصل معك قريباً.']);
    }

    // طلب نظام
    public function requestSystem(Request $request)
    {
        $data = $request->validate([
            'full_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'email'        => 'nullable|email',
            'company_name' => 'nullable|string|max:150',
            'product_id'   => 'required|exists:products,id',
            'notes'        => 'nullable|string|max:1000',
        ]);

        // إنشاء رقم طلب فريد
        $orderNumber = 'ORD-' . strtoupper(substr(uniqid(), -6));

        Order::create([
            'order_number' => $orderNumber,
            'product_id'   => $data['product_id'],
            'notes'        => ($data['company_name'] ?? '') . "\n" . ($data['notes'] ?? ''),
            'status'       => 'pending',
        ]);

        return response()->json(['message' => 'تم استلام طلبك بنجاح، رقم الطلب: ' . $orderNumber]);
    }

    // التقدم لوظيفة
    public function applyJob(Request $request)
    {
        $data = $request->validate([
            'full_name'        => 'required|string|max:100',
            'email'            => 'nullable|email',
            'phone'            => 'required|string|max:30',
            'position'         => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'cover_letter'     => 'nullable|string|max:2000',
            'cv'               => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        unset($data['cv']);
        JobApplication::create($data);

        return response()->json(['message' => 'تم استلام طلب التوظيف بنجاح، سنتواصل معك إذا انطبقت الشروط.']);
    }
}
