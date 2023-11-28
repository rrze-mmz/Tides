<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ClipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'owner' => $this->owner ? [
                'username' => $this->owner->username,
                'fullName' => $this->owner->getFullNameAttribute(),
            ] : null,
            'slug' => $this->slug,
            'episode' => $this->episode,
            'recording_date' => $this->recording_date,
            'semester' => ($this->semester_id) ? $this->semester->name : '',
            'description' => $this->description,
            'password' => Str::mask($this->password, '*', 0),
            'is_public' => $this->is_public,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'allow_comments' => $this->allow_comments,
            'poster_image' => $this->posterImage,
            'series' => $this->series_id ? [
                'series_id' => $this->series_id,
                'series_title' => $this->series->title,
                'series_slug' => $this->series->slug,
                'series_semester' => $this->series->fetchClipsSemester(),
            ] : null,
            'organization' => ($this->organization_id) ? [
                'org_id' => $this->organization_id,
                'org_name' => $this->organization?->name,
                'org_slug' => $this->organization?->slug,
            ] : '',
            'presenters' => ($this->presenters->isNotEmpty()) ? $this->presenters->map(function ($presenter) {
                $image = $presenter->image;

                return [
                    'presenter_id' => $presenter->id,
                    'presenter_fullName' => $presenter->getFullNameAttribute(),
                    'presenter_image_id' => (! is_null($image)) ? (string) $presenter->image_id : 'null',
                    'presenter_image_url' => (! is_null($image)) ? $presenter->getImageUrl() : 'null',
                ];
            })->toArray() : [],
            'language' => $this->language->code,
            'context' => ($this->context_id) ? [
                'context_id' => $this->context_id,
                'context_en_name' => $this->context->en_name,
                'context_de_name' => $this->context->de_name,
            ] : [],
            'format' => ($this->format_id) ? [
                'format_id' => $this->format_id,
                'format_en_name' => $this->format->en_name,
                'format_de_name' => $this->format->de_name,
            ] : [],
            'type' => ($this->type_id) ? [
                'type_id' => $this->type_id,
                'type_en_name' => $this->type->en_name,
                'type_de_name' => $this->type->de_name,
            ] : [],
            'has_time_availability' => $this->has_time_availability,
            'time_availability_start' => $this->time_availability_start,
            'time_availability_end' => $this->time_availability_end,
            'acls' => $this->acls->pluck('name')->unique()->implode(', '),
            'image' => ($this->image_id) ? [
                'image_id' => $this->image_id,
                'image_path' => $this->image->file_name,
            ] : [],
            'poster' => ($this->assets()->count() > 0)
                ? fetchClipPoster($this->latestAsset?->player_preview)
                : '/images/generic_clip_poster_image.png',
        ];
    }
}
