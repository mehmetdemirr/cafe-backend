<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileService
{
    /**
     * Dosya yükle.
     *
     * @param UploadedFile $file
     * @param string $path
     * @return string|false
     */
    public function upload(UploadedFile $file, string $path)
    {
        return $file->store($path, 'public');
    }

    /**
     * Dosya sil.
     *
     * @param string $filePath
     * @return bool
     */
    public function delete(string $filePath): bool
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }
        return false;
    }

    /**
     * Dosya güncelle.
     *
     * @param UploadedFile $file
     * @param string $oldFilePath
     * @param string $path
     * @return string|false
     */
    public function update(UploadedFile $file, string $oldFilePath, string $path)
    {
        $this->delete($oldFilePath); // Eski dosyayı sil
        return $this->upload($file, $path); // Yeni dosyayı yükle
    }

    /**
     * Dosya getir.
     *
     * @param string $filePath
     * @return string|null
     */
    public function get(string $filePath)
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->path($filePath);
        }
        return null;
    }
}
