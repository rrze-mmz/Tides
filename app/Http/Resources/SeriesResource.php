<?php

namespace App\Http\Resources;

use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SeriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $seriesWithLastPublicClip = Series::where('id', $this->id)->withLastPublicClip()->first();
        $seriesAcls = $this->clips->map(function ($clip) {
            return $clip->acls->pluck('name');
        })->flatten()->unique()->values()->implode(', ');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'owner' => $this->owner ? [
                'username' => $this->owner->username,
                'fullName' => $this->owner->getFullNameAttribute(),
            ] : null,
            'slug' => $this->slug,
            'description' => $this->description,
            'password' => Str::mask($this->password, '*', 0),
            'is_public' => $this->is_public,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'organization' => [
                'org_id' => $this->organization_id,
                'org_name' => $this->organization->name,
                'org_slug' => $this->organization->slug,
            ],
            'presenters' => ($this->presenters->isNotEmpty()) ? $this->presenters->map(function ($presenter) {
                $image = $presenter->image;

                return [
                    'presenter_id' => $presenter->id,
                    'presenter_fullName' => $presenter->getFullNameAttribute(),
                    'presenter_image_id' => (! is_null($image)) ? (string) $presenter->image_id : 'null',
                    'presenter_image_url' => (! is_null($image)) ? $presenter->getImageUrl() : 'null',
                ];
            })->toArray() : [],
            'lms_link' => $this->lms_link,
            'image' => [
                'image_id' => $this->image_id,
                'image_path' => $this->image->file_name,
            ],
            'acls' => $seriesAcls,
            'semester' => $this->fetchClipsSemester(),
            'poster' => ($seriesWithLastPublicClip->lastPublicClip)
                ? fetchClipPoster($seriesWithLastPublicClip->lastPublicClip->latestAsset()?->player_preview)
                : '/images/generic_clip_poster_image.png',
            'has_last_public_clip' => ! is_null($seriesWithLastPublicClip->lastPublicClip),
            'last_public_clip' => [$seriesWithLastPublicClip->lastPublicClip],
        ];
    }
}
