<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    // إرسال طلب عرض سعر
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'      => 'nullable|exists:products,id',
            'company_name'    => 'required|string|max:150',
            'contact_name'    => 'required|string|max:100',
            'phone'           => 'required|string|max:30',
            'email'           => 'nullable|email',
            'city'            => 'nullable|string|max:80',
            'employees_count' => 'nullable|integer|min:1|max:100000',
            'requirements'    => 'required|string|max:3000',
            'attachment'      => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('quotes', 'public');
        }

        $data['quote_number'] = 'QT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $quote = Quote::create($data);

        return response()->json([
            'message'      => 'تم استلام طلب عرض السعر بنجاح. سيتواصل معك فريقنا خلال 24 ساعة.',
            'quote_number' => $quote->quote_number,
        ], 201);
    }

    // قائمة طلبات الأسعار (للمشرف)
    public function index(Request $request)
    {
        $quotes = Quote::with('product:id,name_ar')
            ->latest()->paginate(20);
        return response()->json($quotes);
    }

    // تحديث حالة طلب السعر
    public function update(Request $request, Quote $quote)
    {
        $request->validate([
            'status'       => 'in:new,reviewing,quoted,accepted,rejected',
            'admin_notes'  => 'nullable|string',
            'quoted_price' => 'nullable|numeric|min:0',
        ]);

        $quote->update($request->only(['status', 'admin_notes', 'quoted_price']));
        return response()->json($quote->fresh());
    }
}
