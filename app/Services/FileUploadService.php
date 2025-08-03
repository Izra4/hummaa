<?php

namespace App\Services;

use App\Services\Contracts\FileUploadServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileUploadService implements FileUploadServiceInterface
{
    private const DEFAULT_PROFILE_IMAGE = 'images/default-profile.jpeg';
    
    /**
     * Get profile photo URL with fallback to default
     */
    public function getProfilePhotoUrl(?string $photoPath): string
    {
        // Debug log
        Log::info('getProfilePhotoUrl called with: ' . ($photoPath ?? 'null'));
        
        // If no photo path provided, return default
        if (empty($photoPath)) {
            Log::info('No photo path, returning default: ' . asset(self::DEFAULT_PROFILE_IMAGE));
            return asset(self::DEFAULT_PROFILE_IMAGE);
        }
        
        // Check if it's a full URL (for social login avatars)
        if (filter_var($photoPath, FILTER_VALIDATE_URL)) {
            Log::info('Valid URL detected: ' . $photoPath);
            return $photoPath;
        }
        
        // Check if file exists in storage
        if (Storage::disk('public')->exists($photoPath)) {
            $url = asset('storage/' . $photoPath);
            Log::info('File exists in storage, returning: ' . $url);
            return $url;
        }
        
        // Fallback to default if file doesn't exist
        Log::info('File not found, returning default: ' . asset(self::DEFAULT_PROFILE_IMAGE));
        return asset(self::DEFAULT_PROFILE_IMAGE);
    }
    
    /**
     * Upload profile photo
     */
    public function uploadProfilePhoto($file, ?string $oldPhotoPath = null): string
    {
        // Delete old photo if exists (except default)
        if ($oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
            Storage::disk('public')->delete($oldPhotoPath);
        }
        
        // Store new photo
        $path = $file->store('profile-photos', 'public');
        
        return $path;
    }
    
    /**
     * Delete profile photo
     */
    public function deleteProfilePhoto(string $photoPath): bool
    {
        if (Storage::disk('public')->exists($photoPath)) {
            return Storage::disk('public')->delete($photoPath);
        }
        
        return false;
    }
}