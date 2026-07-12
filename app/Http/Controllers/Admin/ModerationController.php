<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Http\Request;

/**
 * Panneau de modération (direction artistique / assistant) : visualise les
 * contenus en attente, les approuve (en confirmant catégorie + tier) ou les
 * rejette avec un motif. Réservé aux rôles admin et assistant.
 */
class ModerationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        if (! in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $status = 'pending';
        }

        $items = Media::with(['producer', 'category', 'reviewer'])
            ->where('moderation_status', $status)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'pending'  => Media::where('moderation_status', 'pending')->count(),
            'approved' => Media::where('moderation_status', 'approved')->count(),
            'rejected' => Media::where('moderation_status', 'rejected')->count(),
        ];

        return view('moderation.index', compact('items', 'status', 'counts'));
    }

    public function show(Media $medium)
    {
        $medium->load(['producer', 'category', 'reviewer', 'seasonsRelation.episodes']);
        $categories = Category::orderBy('name')->get();

        return view('moderation.show', [
            'media' => $medium,
            'categories' => $categories,
            'tiers' => Media::TIERS,
        ]);
    }

    public function approve(Request $request, Media $medium)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'tier' => 'required|in:classique,standard,premium',
        ]);

        $medium->update([
            'category_id' => $data['category_id'],
            'tier' => $data['tier'],
            'moderation_status' => 'approved',
            'rejection_reason' => null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            // Publie immédiatement si aucune date de publication n'est fixée.
            'published_at' => $medium->published_at ?? now(),
        ]);

        return redirect()->route('moderation.index')
            ->with('success', "« {$medium->title} » approuvé et publié.");
    }

    public function reject(Request $request, Media $medium)
    {
        $data = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $medium->update([
            'moderation_status' => 'rejected',
            'rejection_reason' => $data['rejection_reason'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('moderation.index')
            ->with('success', "« {$medium->title} » rejeté.");
    }
}
