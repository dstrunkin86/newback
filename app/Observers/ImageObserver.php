<?php

namespace App\Observers;

use App\Jobs\CompressionImageJob;
use App\Models\Image;
use App\Services\ImageService;

class ImageObserver
{
    /**
     * Handle the Image "created" event.
     * @throws \ImagickException
     */
    public function created(Image $image): void
    {
        if (is_null($image->preview_url)){
            dispatch(new CompressionImageJob($image));
        }
    }

    /**
     * Handle the Image "updated" event.
     */
    public function updated(Image $image): void
    {
        dispatch(new CompressionImageJob($image));
    }

    /**
     * Handle the Image "deleted" event.
     */
    public function deleted(Image $image): void
    {
        //
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
        //
    }
}
