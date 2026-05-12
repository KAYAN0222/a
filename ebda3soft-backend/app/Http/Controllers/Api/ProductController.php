<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // قائمة الأنظمة - للعرض العام
    public function index(Request $request)
    {
        $query = Product::with(['category', 'features', 'images'])
            ->where('is_active', true);

        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->featured) {
            $query->where('is_featured', true);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name_ar', 'like', "%{$request->search}%")
                  ->orWhere('short_desc_ar', 'like', "%{$request->search}%");
            });
        }

        $products = $query->orderBy('sort_order')->get();

        return response()->json($products);
    }

    // تفاصيل نظام واحد
    public function show(string $slug)
    {
        $product = Product::with(['category', 'features', 'images', 'faqs'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json($product);
    }

    // إنشاء نظام جديد (للمشرف)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'       => 'required|string|max:150',
            'name_en'       => 'nullable|string|max:150',
            'category_id'   => 'nullable|exists:categories,id',
            'short_desc_ar' => 'nullable|string',
            'full_desc_ar'  => 'nullable|string',
            'thumbnail'     => 'nullable|string',
            'price'         => 'nullable|numeric',
            'is_featured'   => 'boolean',
            'is_active'     => 'boolean',
            'sort_order'    => 'integer',
        ]);

        $data['slug']       = Str::slug($request->name_en ?? $request->name_ar . '-' . time());
        $data['created_by'] = auth()->id();

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    // تحديث نظام
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name_ar'       => 'sometimes|string|max:150',
            'name_en'       => 'nullable|string|max:150',
            'category_id'   => 'nullable|exists:categories,id',
            'short_desc_ar' => 'nullable|string',
            'full_desc_ar'  => 'nullable|string',
            'thumbnail'     => 'nullable|string',
            'price'         => 'nullable|numeric',
            'is_featured'   => 'boolean',
            'is_active'     => 'boolean',
            'sort_order'    => 'integer',
        ]);

        $product->update($data);
        return response()->json($product->fresh(['category','features','images']));
    }

    // حذف نظام
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'تم حذف النظام بنجاح.']);
    }

    // التصنيفات
    public function categories()
    {
        $categories = Category::withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();
        return response()->json($categories);
    }
}
