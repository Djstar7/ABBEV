<?php

namespace App\Console\Commands;

use App\Models\Episode;
use App\Models\Media;
use App\Services\VideoTranscoder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Convertit en MP4 les vidéos locales EXISTANTES qui ne le sont pas encore
 * (typiquement des .webm), pour qu'elles soient lisibles sur iOS/Android et
 * téléchargeables hors-ligne. Met à jour `video_path` vers le nouveau .mp4.
 *
 *   php artisan videos:transcode-local            # convertit tout
 *   php artisan videos:transcode-local --dry-run  # liste sans convertir
 *   php artisan videos:transcode-local --id=218   # un seul média (film)
 */
class TranscodeLocalVideos extends Command
{
    protected $signature = 'videos:transcode-local
        {--dry-run : Liste les vidéos concernées sans rien convertir}
        {--id= : Ne traiter que ce média (id de film)}';

    protected $description = 'Convertit les vidéos locales non-MP4 (ex. .webm) en MP4 H.264/AAC lisible partout';

    public function handle(VideoTranscoder $transcoder): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if (! $dryRun && ! $transcoder->isAvailable()) {
            $this->error('ffmpeg introuvable sur ce serveur. Installez-le (ex. apt install ffmpeg) '
                .'ou configurez FFMPEG_BIN, puis relancez.');

            return self::FAILURE;
        }

        $targets = $this->collectTargets();

        if ($targets->isEmpty()) {
            $this->info('Aucune vidéo locale à convertir. Tout est déjà en MP4. ✅');

            return self::SUCCESS;
        }

        $this->info($targets->count().' vidéo(s) locale(s) à convertir :');
        $ok = 0;
        $failed = 0;

        foreach ($targets as $t) {
            /** @var Media|Episode $model */
            $model = $t['model'];
            $label = $t['label'];
            $path = $model->video_path;
            $absolute = Storage::disk('public')->path($path);

            $this->line("• [{$t['type']} #{$model->id}] {$label} — {$path}");

            if ($dryRun) {
                continue;
            }

            if (! is_file($absolute)) {
                $this->warn("  ↳ fichier absent sur le disque, ignoré : {$absolute}");
                $failed++;
                continue;
            }

            $newAbsolute = $transcoder->toMp4($absolute);
            if ($newAbsolute === null) {
                $this->error('  ↳ échec de conversion (voir logs).');
                $failed++;
                continue;
            }

            $newRelative = 'uploads/'.basename($newAbsolute);
            $model->update(['video_path' => $newRelative]);

            // Supprime l'ancien fichier non-MP4 (le .mp4 l'a remplacé).
            if ($newAbsolute !== $absolute && is_file($absolute)) {
                @unlink($absolute);
            }

            $this->info("  ↳ converti → {$newRelative}");
            $ok++;
        }

        if ($dryRun) {
            $this->comment('(dry-run : rien n’a été converti)');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->info("Terminé : {$ok} converti(s), {$failed} échec(s).");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Collecte les Media (films) et Episodes locaux dont la vidéo n'est pas MP4.
     *
     * @return \Illuminate\Support\Collection<int, array{type:string, model:Media|Episode, label:string}>
     */
    private function collectTargets()
    {
        $id = $this->option('id');

        $mediaQuery = Media::query()
            ->where('video_provider', 'local')
            ->whereNotNull('video_path');
        if ($id) {
            $mediaQuery->where('id', (int) $id);
        }

        $targets = collect();

        foreach ($mediaQuery->get() as $m) {
            if (VideoTranscoder::needsTranscode($m->video_path)) {
                $targets->push(['type' => 'film', 'model' => $m, 'label' => $m->title]);
            }
        }

        // Les épisodes ne sont traités que si aucun --id de film n'est ciblé.
        if (! $id) {
            $episodes = Episode::query()
                ->where('video_provider', 'local')
                ->whereNotNull('video_path')
                ->get();
            foreach ($episodes as $e) {
                if (VideoTranscoder::needsTranscode($e->video_path)) {
                    $targets->push(['type' => 'épisode', 'model' => $e, 'label' => $e->title ?? ('#'.$e->id)]);
                }
            }
        }

        return $targets;
    }
}
