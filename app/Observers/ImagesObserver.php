<?php

namespace App\Observers;

use App\Models\Clip;
use App\Models\Image;
use App\Models\Podcast;
use App\Models\Presenter;
use App\Models\Series;
use Illuminate\Support\Facades\Storage;

class ImagesObserver
{
    /**
     * Handle the Image "created" event.
     */
    public function created(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "updated" event.
     */
    public function updated(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "deleting" event.
     */
    public function deleting(Image $image)
    {
        //TODO this needs to be tested!
        if ($image->series()->exists()) {
            $image->series()->each(function (Series $series) {
                $series->image_id = config('settings.portal.default_image_id');
                $series->save();
            });
        }
        if ($image->clips()->exists()) {
            $image->clips()->each(function (Clip $clip) {
                $clip->image_id = config('settings.portal.default_image_id');
                $clip->save();
            });
        }
        if ($image->presenters()->exists()) {
            $image->presenters()->each(function (Presenter $presenter) {
                $presenter->image_id = config('settings.portal.default_image_id');
                $presenter->save();
            });
        }
        if ($image->podcasts()->exists()) {
            $image->podcasts()->each(function (Podcast $podcast) {
                $podcast->image_id = config('settings.portal.default_image_id');
                $podcast->save();
            });
        }
    }

    public function deleted(Image $image): void
    {
        Storage::disk('images')->delete($image->file_name);
        Storage::disk('images')->delete('Thumbnails/'.$image->file_name);
    }

    /**
     * Handle the Image "restored" event.
     */
    public function restored(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "force deleted" event.
     */
    public function forceDeleted(Image $image): void
    {
        Storage::disk('images')->delete($image->file_name);
        Storage::disk('images')->delete('Thumbnails/'.$image->file_name);
    }
}
