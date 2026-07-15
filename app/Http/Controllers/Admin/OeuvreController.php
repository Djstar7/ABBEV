<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Oeuvre;
use App\Models\Rubrique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * CRUD des œuvres (livres/documents PDF) rattachées à une rubrique de type
 * « œuvre ». Fichiers stockés sur le disque public (servis avec CORS).
 */
class OeuvreController extends Controller
{
    public function index()
    {
        $oeuvres = Oeuvre::with('rubrique')->orderBy('sort_order')->latest()->paginate(20);

        return view('oeuvres.index', compact('oeuvres'));
    }

    public function create()
    {
        return view('oeuvres.create', ['rubriques' => $this->oeuvreRubriques()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request, true);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['cover_path'] = $this->storeFile($request, 'cover', 'oeuvre-covers');
        $data['file_path'] = $this->storeFile($request, 'file', 'oeuvres');
        $data['is_active'] = $request->boolean('is_active');
        $data['published_at'] = $request->boolean('is_active') ? now() : null;

        Oeuvre::create($data);

        return redirect()->route('oeuvres.index')->with('success', 'Œuvre ajoutée.');
    }

    public function edit(Oeuvre $oeuvre)
    {
        return view('oeuvres.edit', [
            'oeuvre' => $oeuvre,
            'rubriques' => $this->oeuvreRubriques(),
        ]);
    }

    public function update(Request $request, Oeuvre $oeuvre)
    {
        $data = $this->validateData($request, false);
        $data['slug'] = $this->uniqueSlug($data['title'], $oeuvre->id);
        $data['is_active'] = $request->boolean('is_active');

        if ($cover = $this->storeFile($request, 'cover', 'oeuvre-covers')) {
            $this->deleteOld($oeuvre->cover_path);
            $data['cover_path'] = $cover;
        }
        if ($file = $this->storeFile($request, 'file', 'oeuvres')) {
            $this->deleteOld($oeuvre->file_path);
            $data['file_path'] = $file;
        }

        $oeuvre->update($data);

        return redirect()->route('oeuvres.index')->with('success', 'Œuvre mise à jour.');
    }

    public function destroy(Oeuvre $oeuvre)
    {
        $this->deleteOld($oeuvre->cover_path);
        $this->deleteOld($oeuvre->file_path);
        $oeuvre->delete();

        return redirect()->route('oeuvres.index')->with('success', 'Œuvre supprimée.');
    }

    /** @return array<string,mixed> */
    private function validateData(Request $request, bool $creating): array
    {
        $rules = [
            'rubrique_id' => 'required|exists:rubriques,id',
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'pages' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'cover' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
            // PDF obligatoire à la création, optionnel à l'édition.
            'file' => ($creating ? 'required' : 'nullable') . '|file|mimes:pdf|max:51200',
        ];
        $v = $request->validate($rules);

        return [
            'rubrique_id' => $v['rubrique_id'],
            'title' => $v['title'],
            'author' => $v['author'] ?? null,
            'description' => $v['description'] ?? null,
            'pages' => $v['pages'] ?? null,
            'sort_order' => $v['sort_order'] ?? 0,
        ];
    }

    private function storeFile(Request $request, string $field, string $dir): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store($dir, 'public');
    }

    private function deleteOld(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }

    private function oeuvreRubriques()
    {
        return Rubrique::where('content_type', 'oeuvre')->ordered()->get();
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (Oeuvre::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
