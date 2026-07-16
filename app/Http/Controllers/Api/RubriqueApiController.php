<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Http\Resources\SerieResource;
use App\Models\Media;
use App\Models\Oeuvre;
use App\Models\Rubrique;
use App\Services\RubriqueAccessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Rubriques côté mobile : liste des rubriques ACCESSIBLES à l'utilisateur (les
 * rubriques verrouillées par le forfait sont masquées) + contenus d'une
 * rubrique (œuvres/documents ou films/séries selon son type).
 */
class RubriqueApiController extends Controller
{
    public function __construct(private RubriqueAccessService $access)
    {
    }

    /** Rubriques accessibles à l'utilisateur courant. */
    public function index(Request $request): JsonResponse
    {
        $rubriques = $this->access->accessibleRubriques($request->user());

        return response()->json([
            'data' => $rubriques->map(fn (Rubrique $r) => $this->present($r))->values(),
        ]);
    }

    /** Contenus d'une rubrique (403 si non accessible). */
    public function contents(Request $request, Rubrique $rubrique): JsonResponse
    {
        abort_unless($rubrique->is_active, 404);
        abort_unless($this->access->canAccess($request->user(), $rubrique), 403,
            'Cette rubrique est réservée à un forfait supérieur.');

        if ($rubrique->isOeuvre()) {
            $oeuvres = $rubrique->oeuvres()->published()->orderBy('sort_order')->get();

            return response()->json([
                'rubrique' => $this->present($rubrique),
                'type' => 'oeuvre',
                'data' => $oeuvres->map(fn (Oeuvre $o) => $this->presentOeuvre($o))->values(),
            ]);
        }

        // Rubrique 'media' : Avant première (ou tout média approuvé si pas de filtre).
        $query = Media::query()->published();
        if ($rubrique->source_filter === 'rare') {
            $query->rare();
        }
        $movies = (clone $query)->where('type', 'movie')->latest('published_at')->get();
        $series = (clone $query)->where('type', 'series')->latest('published_at')->get();

        return response()->json([
            'rubrique' => $this->present($rubrique),
            'type' => 'media',
            'data' => [
                'movies' => MovieResource::collection($movies),
                'series' => SerieResource::collection($series),
            ],
        ]);
    }

    /** @return array<string,mixed> */
    private function present(Rubrique $r): array
    {
        return [
            'id' => $r->id,
            'name' => $r->name,
            'slug' => $r->slug,
            'description' => $r->description,
            'content_type' => $r->content_type,
            'cover_url' => $this->fileUrl($r->cover_path),
        ];
    }

    /** @return array<string,mixed> */
    private function presentOeuvre(Oeuvre $o): array
    {
        return [
            'id' => $o->id,
            'title' => $o->title,
            'slug' => $o->slug,
            'author' => $o->author,
            'description' => $o->description,
            'pages' => $o->pages,
            'cover_url' => $this->fileUrl($o->cover_path),
            'file_url' => $this->fileUrl($o->file_path),
        ];
    }

    /** URL absolue servie avec CORS (route /media/img/{path}). */
    private function fileUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // URL basée sur l'hôte de la requête (et non config('app.url') qui peut
        // être périmé), pour rester joignable depuis l'app quelle que soit l'IP.
        return url('/media/img/' . ltrim($path, '/'));
    }
}
