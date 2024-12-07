<?php

namespace App\Console\Commands;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\OldArthall\Artist as OldArtist;
use Illuminate\Console\Command;

class importOldArthallArtists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-old-arthall-artists';

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
        $oldArthallPath = env('OLD_ARTHALL_PATH');

        $transferredArtists = Artist::withTrashed()->where('source', 'old_arthall')->pluck('external_id')->toArray();

        $this->line('Import artists from old Arthall');


        $old_artists = OldArtist::with(['paintings'])->whereNotIn('id', $transferredArtists)->get()->toArray();
        $this->line(count($old_artists).' artists will be transferred');

        $resultArray = [];

        foreach ($old_artists as $artist) {
            $artistData = [
                'external_id' => $artist['id'],
                'source' => (!$artist['in_sandbox']) ? 'old_arthall' : 'arthall_sandbox',
                'status' => (($artist['published'] == 'yes')&&($artist['url'])) ? 'accepted':'new',
                'fio' => [
                    "ru" => $artist['name_ru'],
                    "en" => $artist['name_en'],
                ],
                'email' => ($artist['email']) ? $artist['email'] : '-',
                'phone' => ($artist['phone']) ? $artist['phone'] : '-',
                'vk' => '-',
                'url' => $artist['url'],
                'telegram' => '-',
                'city' => '-',
                'country' => ($artist['country']) ? mb_strtoupper($artist['country']) : '-',
                'creative_concept' => [
                    'ru' => strip_tags($artist['about_ru']),
                    'en' => strip_tags($artist['about_en'])
                ],
                'tech_info' => [
                    'facebook' => $artist['facebook'],
                    'instagram' => $artist['instagram'],
                ]
            ];

            $artistPhoto = [];
            if (($artist['photo']) && (file_exists($oldArthallPath . $artist['photo']))) {
                $fileName = str_replace('/storage/photos/', '', $artist['photo']);
                copy($oldArthallPath . $artist['photo'], storage_path('app/public/images/photo' . $fileName));

                $artistPhoto[] = [
                    'url' => '/storage/images/photo' . $fileName,
                    'priority' => 0,
                ];
            }




            $artistArtworks = [];
            if ((isset($artist['paintings'])) && (count($artist['paintings']) > 0)) {
                foreach ($artist['paintings'] as $painting) {
                    if (file_exists($oldArthallPath . $painting['file'])) {
                        $fileName = str_replace('/storage/paintings/', '', $painting['file']);
                        copy($oldArthallPath . $painting['file'], storage_path('app/public/images/' . $fileName));

                        $artistArtworks[] = [
                            'title' => [
                                'ru' => $painting['title_ru'],
                                'en' => $painting['title_en'],
                            ],
                            'description' => [
                                'ru' => strip_tags($painting['additional_info_ru']),
                                'en' => strip_tags($painting['additional_info_en']),
                            ],
                            'year' => $painting['sale_year'],
                            'location' => $painting['sale_location_ru'],
                            'width' => $painting['width'],
                            'height' => $painting['height'],
                            'status' => (($artist['published'] == 'yes')&&($artist['url'])) ? 'accepted':'new',
                            'in_sale' => 0,
                            'images' => [
                                [
                                    'url' => '/storage/images/' . $fileName,
                                    'priority' => 0,
                                ]
                            ],
                        ];
                    }
                }
            }

            $resultArray[] = [
                'data' => $artistData,
                'photo' => $artistPhoto,
                'artworks' => $artistArtworks
            ];
        }

        //dd($resultArray);


        foreach ($resultArray as $artist) {
            $newArtist = Artist::create($artist['data']);
            $newArtist->updateImages($artist['photo']);
            foreach ($artist['artworks'] as $artwork) {
                $artwork['artist_id'] = $newArtist->id;
                $newArtwork = Artwork::create($artwork);
                $newArtwork->updateImages($artwork['images']);
            }
        }

        $this->line('Transfer complete');
    }
}
