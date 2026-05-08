<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::with('category')->where('published_at', '<=', now());

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by featured
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $media = $query->latest('published_at')->paginate($request->get('per_page', 12));

        return response()->json($media);
    }

    public function show($slug)
    {
        $media = Media::with('category')
            ->where('slug', $slug)
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Increment views
        $media->increment('views_count');

        return response()->json($media);
    }

    public function categories()
    {
        $categories = Category::withCount('media')->get();
        return response()->json($categories);
    }

    public function featured()
    {
        $media = Media::with('category')
            ->where('is_featured', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(10)
            ->get();

        return response()->json($media);
    }
}
