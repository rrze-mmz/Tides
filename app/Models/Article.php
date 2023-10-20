<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use App\Models\Traits\Slugable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends BaseModel
{
    use HasFactory;
    use Searchable;
    use Slugable;

    protected array $searchable = [
        'title_en', 'content_en', 'title_de', 'content_de',
    ];

    /**p
     * Route key should be slugged instead of id
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function titleEn(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => html_entity_decode(
                htmlspecialchars_decode(
                    html_entity_decode(html_entity_decode($value, ENT_NOQUOTES, 'UTF-8'))
                )
            )
        );
    }

    protected function titleDe(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => html_entity_decode(
                htmlspecialchars_decode(
                    html_entity_decode(html_entity_decode($value, ENT_NOQUOTES, 'UTF-8'))
                )
            )
        );
    }

    //    protected function contentEn(): Attribute
    //    {
    //        return Attribute::make(
    //            get: fn ($value) => html_entity_decode(
    //                htmlspecialchars_decode(
    //                    html_entity_decode(html_entity_decode($value, ENT_NOQUOTES, 'UTF-8'))
    //                )
    //            )
    //        );
    //    }

    //    protected function contentDe(): Attribute
    //    {
    //        return Attribute::make(
    //            get: fn ($value) => html_entity_decode(
    //                htmlspecialchars_decode(
    //                    html_entity_decode(html_entity_decode($value, ENT_NOQUOTES, 'UTF-8'))
    //                )
    //            )
    //        );
    //    }
}
