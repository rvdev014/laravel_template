<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * @throws Exception
     */
    public function storeFile(
        string $path,
        ?UploadedFile $file,
        ?string $oldPath = null,
        $storageType = 'public'
    ): ?string {
        if (!$file?->isValid()) {
            return null;
        }

        $storage = Storage::disk($storageType);
        if ($oldPath && $storage->exists($oldPath)) {
            $storage->delete($oldPath);
        }

        return $file->store($path, $storageType);
    }

    public function deleteFile(string $path, $storageType = 'public'): void
    {
        $storage = Storage::disk($storageType);
        if ($storage->exists($path)) {
            $storage->delete($path);
        }
    }
}
