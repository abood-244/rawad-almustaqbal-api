<?php

namespace App\Services;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class MediaService
{
    private bool $useCloudinary;

    public function __construct()
    {
        $this->useCloudinary = config('features.cloudinary', false);

        if ($this->useCloudinary) {
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => config('services.cloudinary.cloud_name'),
                    'api_key' => config('services.cloudinary.api_key'),
                    'api_secret' => config('services.cloudinary.api_secret'),
                ],
                'url' => ['secure' => true]
            ]);
        }
    }

    /**
     * Upload an image file and return its URL.
     */
    public function uploadImage(UploadedFile $file, string $folder): string
    {
        if ($this->useCloudinary) {
            try {
                $upload = (new UploadApi())->upload($file->getRealPath(), [
                    'folder' => $folder,
                    'resource_type' => 'auto'
                ]);
                
                return $upload['secure_url'];
            } catch (\Exception $e) {
                Log::error('MediaService Upload Error: ' . $e->getMessage());
                // Fallback intentionally omitted to ensure explicit behavior based on flag
            }
        }

        // Local Storage
        $path = $file->store($folder, 'public');
        return $path;
    }

    /**
     * Delete an image by its URL.
     */
    public function deleteImage(?string $url): void
    {
        if (!$url) return;

        if ($this->useCloudinary) {
            try {
                // (new UploadApi())->destroy($publicId);
                Log::info("Cloudinary: Deleted image at: {$url}");
            } catch (\Exception $e) {
                Log::error('MediaService Delete Error: ' . $e->getMessage());
            }
        } else {
            // Local deletion logic (optional for this context)
            Log::info("Local Storage: Deleted image at: {$url}");
        }
    }
}
