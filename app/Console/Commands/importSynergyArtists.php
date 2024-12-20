<?php
#TODO удалить после переезда на новый Arthall
namespace App\Console\Commands;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Synergy\ArthallRequest;
use App\Models\Synergy\ArtistWorks;
use App\Models\Synergy\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class importSynergyArtists extends Command
{
    public function mapEducation($education)
    {
        $arthallEdu = [
            'place' => [
                'ru' => $education['place']
            ],
            'specialty' => [
                'ru' => $education['specialization']
            ],
            'dates' => array_map('trim', explode('-', $education['date']))

        ];

        return $arthallEdu;
    }

    public function mapExhibition($exhibition)
    {
        $arthallEx = [
            'place' => [
                'ru' => $exhibition['place']
            ],
            'title' => [
                'ru' => $exhibition['name']
            ],
            'dates' => array_map('trim', explode('-', $exhibition['date']))
        ];

        return $arthallEx;
    }

    public function mapPublication($publication)
    {
        $arthallPub = [
            'place' => [
                'ru' => $publication['place']
            ],
            'title' => [
                'ru' => $publication['name']
            ],
            'link' => $publication['url']
        ];

        return $arthallPub;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-synergy-artists';

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
        $synergyPath = env('SYNERGY_PATH');


        $transferredArtists = Artist::withTrashed()->where('source', 'synergy')->pluck('external_id')->toArray();
        $this->line('Import artists from Synergy');


        $synergy_artists = ArthallRequest::with(['user'])->whereNotIn('user_id', $transferredArtists)->has('user.artistCv')->get();
        //dd($synergy_artists->toArray());

        $this->line(count($synergy_artists) . ' artists will be transferred');

        $resultArray = [];

        foreach ($synergy_artists->toArray() as $artist) {
            //dd($artist);

            $artistData = [
                'external_id' => $artist['user_id'],
                'source' => 'synergy',
                'status' => 'new',
                'fio' => [
                    "ru" => $artist['user']['name'],
                ],
                'email' => ($artist['user']['email']) ? $artist['user']['email'] : '-',
                'phone' => ($artist['user']['phone']) ? $artist['user']['phone'] : '-',
                'vk' => isset($artist['user']['social']['vk']) ? $artist['user']['social']['vk'] : '-',
                'telegram' => isset($artist['user']['social']['telegram']) ? $artist['user']['social']['telegram'] : '-',
                'city' => isset($artist['user']['artist_cv']['city']) ? $artist['user']['artist_cv']['city'] : '-',
                'country' => 'RU',
                'creative_concept' => [
                    'ru' => isset($artist['user']['artist_cv']['concept_creativity']) ? strip_tags($artist['user']['artist_cv']['concept_creativity']) : '-',
                ],
                'tech_info' => [
                    'work_styles' => $artist['user']['artist_cv']['style_work'],
                    'work_themes' => $artist['user']['artist_cv']['themes_works'],
                ],
                'education' => array_map(array($this, 'mapEducation'), $artist['user']['artist_cv']['educations']),
                'qualification' => array_map(array($this, 'mapEducation'), $artist['user']['artist_cv']['qualifications']),
                'exhibitions' => array_map(array($this, 'mapExhibition'), $artist['user']['artist_cv']['exhibition_projects']),
                'publications' => array_map(array($this, 'mapPublication'), $artist['user']['artist_cv']['publications']),
            ];

            $artistPhoto = [];

            if (($artist['user']['avatar']) && (file_exists($synergyPath . $artist['user']['avatar']['url']))) {
                $fileName = str_replace('/storage/images/', '', $artist['user']['avatar']['url']);
                copy($synergyPath . $artist['user']['avatar']['url'], storage_path('app/public/images/photo' . $fileName));

                $artistPhoto[] = [
                    'url' => '/storage/images/photo' . $fileName,
                    'priority' => 0,
                ];
            }




            $artistArtworks = [];
            if ((isset($artist['user']['artist_works'])) && (count($artist['user']['artist_works']) > 0)) {
                foreach ($artist['user']['artist_works'] as $painting) {
                    if (isset($painting['image'])) {
                        $fileName = str_replace('/storage/images/', '', $painting['image']['url']);

                        if (file_exists($synergyPath . '/storage/images/' . $fileName)) {

                            copy($synergyPath . '/storage/images/' . $fileName, storage_path('app/public/images/' . $fileName));

                            $artistArtworks[] = [
                                'title' => [
                                    'ru' => $painting['title'],
                                ],
                                'description' => [
                                    'ru' => $painting['description'],
                                ],
                                'year' => $painting['year'],
                                'location' => isset($artist['user']['artist_cv']['city']) ? $artist['user']['artist_cv']['city'] : '-',
                                'width' => null,
                                'height' => null,
                                'status' => 'new',
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
