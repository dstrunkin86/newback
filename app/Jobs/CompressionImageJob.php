<?php

namespace App\Jobs;

use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CompressionImageJob implements ShouldQueue
{
    use Queueable;

    public Image $image;

    /**
     * Create a new job instance.
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     * @throws \ImagickException
     */
    public function handle(): void
    {
        $path = public_path($this->image->url);

        if (file_exists($path)){
            ImageService::compression($path);

            $this->image->preview_url = ImageService::getPreviewPath($this->image->url);

            $this->image->save();
        }
    }
}
