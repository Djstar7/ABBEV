<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EpisodeController extends Controller
{
    /**
     * Afficher la page de gestion des saisons et épisodes d'une série.
     */
    public function index(Media $media)
    {
        // Vérifier que c'est bien une série
        if (!$media->isSeries()) {
            return redirect()->route('media.index')->with('error', 'Ce média n\'est pas une série.');
        }

        $seasons = $media->seasonsRelation()->with('episodes')->get();

        return view('episodes.index', compact('media', 'seasons'));
    }

    /**
     * Créer une nouvelle saison pour une série.
     */
    public function createSeason(Request $request, Media $media)
    {
        $validated = $request->validate([
            'season_number' => 'required|integer|min:1',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
        ]);

        $season = $media->seasonsRelation()->create($validated);

        return redirect()->route('episodes.index', $media)->with('success', "Saison {$season->season_number} créée avec succès !");
    }

    /**
     * Afficher le formulaire d'ajout d'épisode.
     */
    public function create(Season $season)
    {
        return view('episodes.create', compact('season'));
    }

    /**
     * Enregistrer un nouvel épisode.
     */
    public function store(Request $request, Season $season)
    {
        $validated = $request->validate([
            'episode_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'video_path' => 'required|string', // Le chemin est fourni par FilePond
            'thumbnail' => 'nullable|image|max:10240',
            'published_at' => 'nullable|date',
        ]);

        // Upload de la vignette si fournie
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $episode = $season->episodes()->create($validated);

        // Mettre à jour le compteur d'épisodes de la saison
        $season->updateEpisodesCount();

        return redirect()->route('episodes.index', $season->media)->with('success', "Épisode {$episode->episode_number} ajouté avec succès !");
    }

    /**
     * Afficher le formulaire d'édition d'un épisode.
     */
    public function edit(Episode $episode)
    {
        $season = $episode->season;
        return view('episodes.edit', compact('episode', 'season'));
    }

    /**
     * Mettre à jour un épisode.
     */
    public function update(Request $request, Episode $episode)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'video_path' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:10240',
            'published_at' => 'nullable|date',
        ]);

        // Upload de la nouvelle vignette si fournie
        if ($request->hasFile('thumbnail')) {
            // Supprimer l'ancienne vignette
            if ($episode->thumbnail_path) {
                Storage::disk('public')->delete($episode->thumbnail_path);
            }
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $episode->update($validated);

        return redirect()->route('episodes.index', $episode->season->media)->with('success', "Épisode {$episode->episode_number} mis à jour avec succès !");
    }

    /**
     * Supprimer un épisode.
     */
    public function destroy(Episode $episode)
    {
        $season = $episode->season;
        $media = $season->media;

        // Supprimer les fichiers associés
        if ($episode->video_path) {
            Storage::disk('public')->delete($episode->video_path);
        }
        if ($episode->thumbnail_path) {
            Storage::disk('public')->delete($episode->thumbnail_path);
        }

        $episode->delete();

        // Mettre à jour le compteur
        $season->updateEpisodesCount();

        return redirect()->route('episodes.index', $media)->with('success', 'Épisode supprimé avec succès !');
    }

    /**
     * Supprimer une saison complète avec tous ses épisodes.
     */
    public function destroySeason(Season $season)
    {
        $media = $season->media;

        // Supprimer tous les fichiers vidéos des épisodes
        foreach ($season->episodes as $episode) {
            if ($episode->video_path) {
                Storage::disk('public')->delete($episode->video_path);
            }
            if ($episode->thumbnail_path) {
                Storage::disk('public')->delete($episode->thumbnail_path);
            }
        }

        $seasonNumber = $season->season_number;
        $season->delete();

        return redirect()->route('episodes.index', $media)->with('success', "Saison {$seasonNumber} supprimée avec succès !");
    }
}
