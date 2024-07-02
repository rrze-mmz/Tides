<?php

namespace App\Models;

use App\Models\Traits\Presentable;
use App\Models\Traits\RecordsActivity;
use App\Models\Traits\Searchable;
use App\Models\Traits\Slugable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PodcastEpisode extends BaseModel
{
    use HasFactory;
    use Presentable;
    use RecordsActivity;
    use Searchable;
    use Slugable;

    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => html_entity_decode(
                htmlspecialchars_decode(
                    html_entity_decode(html_entity_decode($value, ENT_NOQUOTES, 'UTF-8'))
                )
            )
        );
    }

    protected function transcription(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => html_entity_decode(
                htmlspecialchars_decode(
                    html_entity_decode(html_entity_decode($value, ENT_NOQUOTES, 'UTF-8'))
                )
            )
        );
    }
}
