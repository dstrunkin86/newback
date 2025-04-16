<?php

namespace App\Console\Commands;

use App\Jobs\CompressionImageJob;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImageCompressionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:compression';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('image:compression start-' . Carbon::now()->format('Y-m-d H:i:s'));

        Image::query()
            ->whereNull('preview_url')
            ->chunk(1000, function ($images){
                foreach ($images as $image){
                    dispatch(new CompressionImageJob($image));
                }
            });

        $this->info('image:compression finish-' . Carbon::now()->format('Y-m-d H:i:s'));
    }
}
