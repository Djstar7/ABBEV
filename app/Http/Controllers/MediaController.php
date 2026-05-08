<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::with('category')->latest()->paginate(12);
        return view('media.index', compact('media'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('media.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:movie,series',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'seasons' => 'nullable|integer|min:1',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv,webm|max:2048000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        // Convert duration from minutes to seconds if provided
        if (isset($validated['duration'])) {
            $validated['duration'] = $validated['duration'] * 60;
        }

        // Upload video
        if ($request->hasFile('video')) {
            $validated['video_path'] = $request->file('video')->store('videos', 'public');
        }

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Upload cover
        if ($request->hasFile('cover')) {
            $validated['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        // Upload banner
        if ($request->hasFile('banner')) {
            $validated['banner_path'] = $request->file('banner')->store('banners', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');

        Media::create($validated);

        return redirect()->route('media.index')
            ->with('success', 'Média ajouté avec succès.');
    }

    public function show(Media $medium)
    {
        $medium->load('category');
        return view('media.show', compact('medium'));
    }

    public function edit(Media $medium)
    {
        $categories = Category::all();
        return view('media.edit', compact('medium', 'categories'));
    }

    public function update(Request $request, Media $medium)
    {
        $validated = $request->validate([
            'type' => 'required|in:movie,series',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'seasons' => 'nullable|integer|min:1',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv,webm|max:2048000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        // Convert duration from minutes to seconds if provided
        if (isset($validated['duration'])) {
            $validated['duration'] = $validated['duration'] * 60;
        }

        // Upload new video if provided
        if ($request->hasFile('video')) {
            // Delete old video (only if local path)
            if ($medium->video_path && !str_starts_with($medium->video_path, 'http')) {
                Storage::disk('public')->delete($medium->video_path);
            }
            $validated['video_path'] = $request->file('video')->store('videos', 'public');
        }

        // Upload new thumbnail if provided
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail (only if local path)
            if ($medium->thumbnail_path && !str_starts_with($medium->thumbnail_path, 'http')) {
                Storage::disk('public')->delete($medium->thumbnail_path);
            }
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Upload new cover if provided
        if ($request->hasFile('cover')) {
            // Delete old cover (only if local path)
            if ($medium->cover_path && !str_starts_with($medium->cover_path, 'http')) {
                Storage::disk('public')->delete($medium->cover_path);
            }
            $validated['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        // Upload new banner if provided
        if ($request->hasFile('banner')) {
            // Delete old banner (only if local path)
            if ($medium->banner_path && !str_starts_with($medium->banner_path, 'http')) {
                Storage::disk('public')->delete($medium->banner_path);
            }
            $validated['banner_path'] = $request->file('banner')->store('banners', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');

        $medium->update($validated);

        return redirect()->route('media.index')
            ->with('success', 'Média mis à jour avec succès.');
    }

    public function destroy(Media $medium)
    {
        // Delete associated files (only if local paths)
        if ($medium->video_path && !str_starts_with($medium->video_path, 'http')) {
            Storage::disk('public')->delete($medium->video_path);
        }
        if ($medium->thumbnail_path && !str_starts_with($medium->thumbnail_path, 'http')) {
            Storage::disk('public')->delete($medium->thumbnail_path);
        }
        if ($medium->cover_path && !str_starts_with($medium->cover_path, 'http')) {
            Storage::disk('public')->delete($medium->cover_path);
        }
        if ($medium->banner_path && !str_starts_with($medium->banner_path, 'http')) {
            Storage::disk('public')->delete($medium->banner_path);
        }

        $medium->delete();

        return redirect()->route('media.index')
            ->with('success', 'Média supprimé avec succès.');
    }
}
