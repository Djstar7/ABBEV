<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class ChunkUploadController extends Controller
{
    /**
     * Gérer l'upload de fichiers par chunks avec support des très gros fichiers.
     * Compatible avec FilePond, Dropzone, et autres bibliothèques d'upload chunked.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        // Créer le file receiver
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        // Vérifier si l'upload est valide
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        // Recevoir le fichier
        $save = $receiver->receive();

        // Vérifier si l'upload est terminé
        if ($save->isFinished()) {
            // L'upload est terminé, sauvegarder le fichier
            return $this->saveFile($save->getFile());
        }

        // On retourne le pourcentage de progression
        $handler = $save->handler();

        return response()->json([
            'done' => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    /**
     * Sauvegarder le fichier uploadé complètement.
     *
     * @param UploadedFile $file
     * @return JsonResponse
     */
    protected function saveFile(UploadedFile $file): JsonResponse
    {
        $fileName = $this->createFilename($file);

        // Déterminer le dossier de destination selon le type de fichier
        $folder = $this->getStorageFolder($file);

        // Déplacer le fichier vers le stockage permanent
        $filePath = $file->storeAs($folder, $fileName, 'public');

        // Supprimer les fichiers temporaires de chunks
        unlink($file->getPathname());

        return response()->json([
            'path' => $filePath,
            'name' => $fileName,
            'mime_type' => $file->getMimeType(),
            'url' => asset('storage/' . $filePath),
            'size' => $file->getSize(),
            'status' => true,
            'message' => 'Fichier uploadé avec succès'
        ]);
    }

    /**
     * Créer un nom de fichier unique.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function createFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace('.' . $extension, '', $file->getClientOriginalName());

        // Nettoyer le nom de fichier
        $filename = preg_replace('/[^A-Za-z0-9\-]/', '_', $filename);

        // Ajouter un timestamp pour l'unicité
        return $filename . '_' . time() . '.' . $extension;
    }

    /**
     * Déterminer le dossier de stockage selon le type MIME.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function getStorageFolder(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'video/')) {
            return 'videos';
        } elseif (str_starts_with($mimeType, 'image/')) {
            return 'images';
        } else {
            return 'files';
        }
    }

    /**
     * Supprimer un fichier uploadé (pour l'annulation).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $path = storage_path('app/public/' . $request->path);

        if (file_exists($path)) {
            unlink($path);

            return response()->json([
                'status' => true,
                'message' => 'Fichier supprimé avec succès'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Fichier non trouvé'
        ], 404);
    }
}
