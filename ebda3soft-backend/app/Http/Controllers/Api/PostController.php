<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // قائمة الأخبار والمقالات
    public function index(Request $request)
    {
        $query = Post::with('author:id,name')
            ->published()
            ->latest('published_at');

        if ($request->type) {
            $query->where('post_type', $request->type);
        }

        $posts = $query->paginate($request->per_page ?? 9);
        return response()->json($posts);
    }

    // تفاصيل مقال واحد
    public function show(string $slug)
    {
        $post = Post::with(['author:id,name', 'tags'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $post->increment('views_count');
        return response()->json($post);
    }

    // إنشاء مقال (للمحرر/المشرف)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title_ar'     => 'required|string|max:255',
            'content_ar'   => 'nullable|string',
            'excerpt_ar'   => 'nullable|string',
            'thumbnail'    => 'nullable|string',
            'post_type'    => 'in:news,blog,event',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $data['slug']      = Str::slug($request->title_ar . '-' . time());
        $data['author_id'] = auth()->id();
        if ($data['is_published'] ?? false) {
            $data['published_at'] = $data['published_at'] ?? now();
        }

        $post = Post::create($data);
        return response()->json($post, 201);
    }

    // تحديث مقال
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title_ar'     => 'sometimes|string|max:255',
            'content_ar'   => 'nullable|string',
            'excerpt_ar'   => 'nullable|string',
            'thumbnail'    => 'nullable|string',
            'post_type'    => 'in:news,blog,event',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $post->update($data);
        return response()->json($post->fresh());
    }

    // حذف مقال
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'تم حذف المقال بنجاح.']);
    }
}
