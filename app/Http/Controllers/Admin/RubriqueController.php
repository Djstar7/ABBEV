<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Rubrique;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * CRUD des rubriques (admin) : sections gated par forfait, de type « œuvre »
 * (documents/PDF) ou « media » (films/séries, ex. contenu rare).
 */
class RubriqueController extends Controller
{
    public function index()
    {
        $rubriques = Rubrique::ordered()
            ->withCount('oeuvres')
            ->with('plans:id,name')
            ->get();

        // Comptage des médias éligibles pour les rubriques de type « media »
        // (mêmes filtres que l'API mobile : contenu publié + filtre 'rare').
        foreach ($rubriques as $r) {
            if ($r->content_type === 'media') {
                $q = Media::query()->published();
                if ($r->source_filter === 'rare') {
                    $q->rare();
                }
                $r->media_count = $q->count();
            }
        }

        return view('rubriques.index', compact('rubriques'));
    }

    public function create()
    {
        return view('rubriques.create', [
            'plans' => SubscriptionPlan::orderBy('price')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['cover_path'] = $this->handleCover($request);

        $rubrique = Rubrique::create($data);
        $rubrique->plans()->sync($request->input('plans', []));

        return redirect()->route('rubriques.index')
            ->with('success', 'Rubrique créée.');
    }

    public function edit(Rubrique $rubrique)
    {
        return view('rubriques.edit', [
            'rubrique' => $rubrique->load('plans:id'),
            'plans' => SubscriptionPlan::orderBy('price')->get(),
        ]);
    }

    public function update(Request $request, Rubrique $rubrique)
    {
        $data = $this->validateData($request, $rubrique);
        $data['slug'] = $this->uniqueSlug($data['name'], $rubrique->id);
        if ($cover = $this->handleCover($request)) {
            $data['cover_path'] = $cover;
        }

        $rubrique->update($data);
        $rubrique->plans()->sync($request->input('plans', []));

        return redirect()->route('rubriques.index')
            ->with('success', 'Rubrique mise à jour.');
    }

    public function destroy(Rubrique $rubrique)
    {
        $rubrique->delete();

        return redirect()->route('rubriques.index')
            ->with('success', 'Rubrique supprimée.');
    }

    /** @return array<string,mixed> */
    private function validateData(Request $request, ?Rubrique $rubrique = null): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:oeuvre,media',
            'source_filter' => 'nullable|in:rare',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'cover' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
            'plans' => 'nullable|array',
            'plans.*' => 'integer|exists:subscription_plans,id',
        ]);

        return [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'content_type' => $validated['content_type'],
            // Le filtre 'rare' n'a de sens que pour une rubrique de type media.
            'source_filter' => $validated['content_type'] === 'media'
                ? ($validated['source_filter'] ?? null)
                : null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function handleCover(Request $request): ?string
    {
        if (! $request->hasFile('cover')) {
            return null;
        }

        return $request->file('cover')->store('rubrique-covers', 'public');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Rubrique::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
