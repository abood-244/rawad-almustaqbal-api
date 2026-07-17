<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function __construct(private MediaService $mediaService)
    {
    }

    public function createProject(array $data, ?UploadedFile $imageFile): Project
    {
        $imagePath = $this->resolveImagePath($data, $imageFile);

        return Project::create([
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'],
            'image_path' => $imagePath,
        ]);
    }

    public function updateProject(Project $project, array $data, ?UploadedFile $imageFile): Project
    {
        $oldImagePath = $project->image_path;
        $newImagePath = $this->resolveImagePath($data, $imageFile);

        $project->update([
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'],
            'image_path' => $newImagePath ?: $oldImagePath,
        ]);

        if ($imageFile && $oldImagePath && $oldImagePath !== $newImagePath) {
            $this->mediaService->deleteImage($oldImagePath);
        }

        return $project;
    }

    public function deleteProject(Project $project): void
    {
        if ($project->image_path) {
            $this->mediaService->deleteImage($project->image_path);
        }
        
        $project->delete();
    }

    private function resolveImagePath(array $data, ?UploadedFile $imageFile): ?string
    {
        if ($imageFile) {
            return $this->mediaService->uploadImage($imageFile, 'projects');
        }

        return $data['image_url'] ?? null;
    }
}
