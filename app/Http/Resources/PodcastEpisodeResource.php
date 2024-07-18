<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PodcastEpisodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'notes' => $this->notes,
            'transcription' => $this->transcription,
            'owner' => $this->owner ? [
                'username' => $this->owner->username,
                'fullName' => $this->owner->getFullNameAttribute(),
            ] : null,
            'slug' => $this->slug,
            'cover' => ($this->image_id) ? [
                'image_id' => $this->image_id,
                'image_path' => $this->cover->file_name,
            ] : [],
            'is_published' => $this->is_published,
            'website_url' => $this->website_url,
            'spotify_url' => $this->spotify_url,
            'apple_podcasts_url' => $this->apple_podcasts_url,
            'google_podcasts_url' => $this->google_podcasts_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'hosts' => $this->getPrimaryPresenters()->map(fn ($presenter) => $presenter->full_name)->join(', '),
            'guests' => $this->getPrimaryPresenters(primary: true)
                ->map(fn ($presenter) => $presenter->full_name)->join(', '),
        ];
    }
}
