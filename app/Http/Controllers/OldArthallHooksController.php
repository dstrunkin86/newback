<?php
#TODO удалить после переезда на новый Arthall

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\User;
use Illuminate\Http\Request;

class OldArthallHooksController extends Controller
{
    private function trans($string) {
        $string = transliterator_transliterate("Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();", $string);
        $string = preg_replace('/[-\s]+/', '-', $string);
        return trim($string, '-');
    }

    /**
     * @param  Request  $request
     * @return array
     */
    public function emptyArray(Request $request)
    {
        return [
            'synergy_paintings' => [],
            'paintings' => $this->artworksList($request)
        ];
    }


    public function returnSuccess()
    {
        return [
            "message" => "Success"
        ];
    }

    public function mobileLang(Request $request)
    {
        $mobileLang['en'] = array('help' => array('autoplay' => 'Enable slideshow', 'slider' => 'Flip through the gallery', 'artist' => 'Gallery and manifesto of the artist'), 'menu' => array('subscriptions' => 'My subscriptions', 'gallery' => 'My gallery', 'artists' => 'Artists in the gallery', 'notification' => 'Notifications', 'settings' => 'Settings', 'buy_painting' => 'Buy paintings', 'achievements' => 'My achievements', 'support' => 'Ask a question', 'close' => 'Close', 'share' => 'Share', 'add' => 'Add to your gallery', 'zoom' => 'Zoom'), 'popover' => array('title' => 'Title', 'rename' => 'Edit title', 'add' => 'Add to ...', 'move' => 'Move to ...', 'delete' => 'Delete?', 'select_hall' => 'Select gallery', 'agree' => 'OK', 'disagree' => 'Cancel'), 'order' => array('painting' => array('title' => 'Order a painting', 'size' => 'Size', 'technique' => 'Technique', 'material' => 'Material', 'create_date' => 'Year', 'location' => 'Location', 'order' => 'Order', 'interior' => 'See the size in the interior', 'original' => 'Original'), 'cm' => 'cm', 'year' => 'year'), 'checkout' => array('title' => 'Checkout', 'select' => 'Selected', 'painting' => 'the original painting', 'hint' => 'Please provide your contact information and we will contact you about your order', 'name' => array('label' => 'Your name', 'placeholder' => 'Enter your name'), 'tel' => array('label' => 'Your phone number', 'placeholder' => 'Enter your phone number'), 'mail' => array('label' => 'Your email address', 'placeholder' => 'Enter your email address'), 'address' => array('label' => 'Delivery address', 'placeholder' => 'Enter your delivery address'), 'send' => 'Send'), 'success' => array('title' => 'Request sent successfully', 'thanks' => 'Thank you for ordering!', 'hint' => 'We will contact you as soon as possible', 'close' => 'Close'), 'buyPainting' => array('title' => 'Paintings for sale'), 'painting' => array('sale_price' => 'sale_price_usd', 'title' => 'title_en', 'sale_technique' => 'sale_technique_en', 'sale_location' => 'sale_location_en', 'additional_info' => 'additional_info_en'), 'look_gallery' => 'Look at my gallery in Arthall', 'gallery_footer' => 'For more rooms, view images, rate, share with friends and get more achievements', 'empty_notification' => 'There\'s nothing here!', 'empty_hall' => 'This room is empty!', 'empty_artist' => 'There is no artist', 'search' => 'Search', 'empty_subscriptions' => 'You have no active subscriptions', 'hall' => '+ hall', 'share' => 'Share', 'hide_artist' => 'Hide artist', 'manifest' => 'Manifest', 'gallery' => 'Gallery', 'language' => 'Language', 'paintings_sub' => 'Paintings subjects', 'ask_question' => 'Ask question', 'hidden_artists' => 'Hidden artists', 'main' => 'Main', 'topic' => 'Content', 'achievement' => 'Achievement', 'themes_paintings' => 'Themes of paintings', 'subscribed' => 'You are subscribed', 'subscribe' => 'Subscribe', 'artist_hidden' => 'Artist hidden', 'hide' => 'Hide', 'close' => 'Close', 'view' => 'View', 'cancel' => 'Cancel', 'back' => 'Back', 'update' => 'Update', 'continue' => 'Continue', 'update_your_app' => 'Update your app', 'update_available' => 'Update available', 'your_question' => 'Your question here ...', 'artHall_app_question' => 'ArtHall app question', 'look_painting' => 'Look at this painting: ', 'look_artist' => 'Look at this artist: ', 'new_badge' => 'I\'ve got a new badge in Arthall app: ', 'select_hall' => 'Select hall', 'full_hall' => '(Hall is full)', 'currency' => '$', 'loader' => 'Data loading');
        $mobileLang['ru'] = array('help' => array('autoplay' => 'Включить слайд шоу', 'slider' => 'Листать галерею', 'artist' => 'Галерея и манифест художника'), 'menu' => array('subscriptions' => 'Мои подписки', 'gallery' => 'Моя галерея', 'artists' => 'Художники в галерее', 'notification' => 'Уведомления', 'settings' => 'Настройки', 'buy_painting' => 'Приобрести картины', 'achievements' => 'Мои достижения', 'support' => 'Задать вопрос', 'close' => 'Отмена', 'share' => 'Поделиться', 'add' => 'Добавить в свою галерею', 'zoom' => 'Приблизить'), 'popover' => array('title' => 'Название', 'rename' => 'Изменить название', 'add' => 'Добавить в ...', 'move' => 'Переместить в ...', 'delete' => 'Удалить?', 'select_hall' => 'Выбрать галерею', 'agree' => 'ОК', 'disagree' => 'Отмена'), 'order' => array('painting' => array('title' => 'Заказать картину', 'size' => 'Размер', 'technique' => 'Техника', 'material' => 'Материал', 'create_date' => 'Дата создания', 'location' => 'Место нахождения', 'order' => 'Заказать', 'interior' => 'Посмотреть размер в интерьере', 'original' => 'Оригинал'), 'cm' => 'см', 'year' => 'г.'), 'checkout' => array('title' => 'Оформление заказа', 'select' => 'Выбрано', 'painting' => 'оригинал картины', 'hint' => 'Введите контактные данные и мы свяжемся с Вами по поводу Вашего заказа', 'name' => array('label' => 'Ваше имя', 'placeholder' => 'Дмитрий Иванов'), 'tel' => array('label' => 'Контактный телефон', 'placeholder' => 'Введите ваш телефон'), 'mail' => array('label' => 'Ваш e-mail', 'placeholder' => 'Введите вашу электронную почту'), 'address' => array('label' => 'Адрес доставки', 'placeholder' => 'Введите адрес доставки'), 'send' => 'Отправить'), 'success' => array('title' => 'Заявка успешно отправлена', 'thanks' => 'Спасибо за заказ!', 'hint' => 'Мы свяжемся с вами в ближайшее время', 'close' => 'Закрыть'), 'buyPainting' => array('title' => 'Картины к продаже'), 'painting' => array('sale_price' => 'sale_price_rub', 'title' => 'title_ru', 'sale_technique' => 'sale_technique_ru', 'sale_location' => 'sale_location_ru', 'additional_info' => 'additional_info_ru'), 'look_gallery' => 'Посмотри мою галерею в приложении Arthall', 'gallery_footer' => 'Для получения дополнительных залов, просматривайте картины, оценивайте, делитесь с друзьями и получайте больше достижений', 'empty_notification' => 'Здесь ничего нет!', 'empty_hall' => 'Этот зал пуст!', 'empty_artist' => 'Нет ни одного художника', 'search' => 'Поиск', 'empty_subscriptions' => 'У Вас нет активных подписок', 'hall' => '+ зал', 'share' => 'Поделиться', 'hide_artist' => 'Скрывать художника', 'manifest' => 'Манифест', 'gallery' => 'Галерея', 'language' => 'Язык:', 'paintings_sub' => 'Тематика картин', 'ask_question' => 'Задать вопрос', 'hidden_artists' => 'Фильтруемые художники', 'main' => 'Основные', 'topic' => 'Контент', 'achievement' => 'Достижения', 'themes_paintings' => 'Тематика картин', 'subscribed' => 'Вы подписаны', 'subscribe' => 'Подписаться', 'artist_hidden' => 'Художник скрыт', 'hide' => 'Скрывать', 'close' => 'Закрыть', 'view' => 'Смотреть', 'cancel' => 'Отмена', 'back' => 'Назад', 'update' => 'Обновить', 'continue' => 'Продолжить', 'update_your_app' => 'Обновите приложение', 'update_available' => 'Доступно обновление', 'your_question' => 'Опишите Ваш вопрос...', 'artHall_app_question' => 'Вопрос из приложения ArtHall', 'look_painting' => 'Смотри какая интересная картина: ', 'look_artist' => 'Смотри какой интересный художник: ', 'new_badge' => 'У меня новое достижение в приложении ArtHall: ', 'select_hall' => 'Выберите зал', 'full_hall' => '(Зал заполнен)', 'currency' => '₽', 'loader' => 'Загрузка данных');


        if (isset($mobileLang[$request->id])) {
            return response()->json($mobileLang[$request->id], 200);
        } else {
            return response()->json(['result' => 'Error', 'message' => 'No such language'], 403);
        }
    }

    public function desktopLang(Request $request)
    {
        $desktopLang['ru'] = [
            "language" => "Язык",
            "title" => "ArtHall - лента работ современных художников",
            "mainTitle" => "Лента работ современных художников",
            "contactTitle" => "Хотите добавить Ваши работы в ArtHall?",
            "contactAbout" => "Расскажите о себе",
            "name" => "Ваше имя",
            "about" => "Коротко о Вас",
            "email" => "Email",
            "singUp" => "Регистрация",
            "singIn" => "Уже регистрировались? Войдите здесь",
            "oferta" => "Авторское соглашение",
            "byClicking" => "Нажимая 'Регистрация' вы принимаете условия Соглашения",
            "gallery" => "Галерея",
            "manifest" => "Манифест",
            "subscribe" => "Подписаться",
            "loading" => "Загрузка",
            "modalMessage" => "Установите приложение ArtHall, чтобы использовать все функции",
            "sendAccess" => "Форма успешно отправлена!",
            "willContact" => "Мы свяжемся с Вами в ближайшее время",
            "painting_links" => "Ссылки на Ваши работы",
            "installApp" => "Установите приложение ArtHall",
            "artistsTitle" => "Художники в галерее"
        ];
        $desktopLang['en'] = [
            "language" => "Language",
            "title" => "ArtHall - modern artists' paintings feed",
            "mainTitle" => "Modern artists' paintings feed",
            "contactTitle" => "Want to add your paintings to ArtHall?",
            "contactAbout" => "Tell us about yourself",
            "name" => "Your name",
            "about" => "Briefly about you",
            "email" => "Email",
            "singUp" => "Sing up",
            "singIn" => "Already Registered? Login here",
            "oferta" => "Copyright agreement",
            "byClicking" => "By clicking the 'Register' button, you accept the terms",
            "gallery" => "Gallery",
            "manifest" => "Manifest",
            "subscribe" => "Subscribe",
            "loading" => "Loading",
            "modalMessage" => "Install ArtHall app to gain access to all features",
            "sendAccess" => "Form sent successfully!",
            "willContact" => "We will contact you shortly",
            "painting_links" => "Links to your paintings",
            "installApp" => "Download an ArtHall app",
            "artistsTitle" => "Artists in the gallery"
        ];

        //echo $request->id; die();

        if (isset($desktopLang[$request->id])) {
            return response()->json($desktopLang[$request->id], 200);
        } else {
            return response()->json(['result' => 'Error', 'message' => 'No such language'], 403);
        }
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'lang' => 'string|max:10',
            'device_name' => 'required|string|max:25',
            'fcm_token' => 'string'
        ]);

        $user = User::create($request->all());
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json(['user_id' => $user->id, 'token' => $token], 200);
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'lang' => 'string|max:10',
            'fcm_token' => 'string'
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 422);
        }

        $user->fill($request->all());
        $user->save();

        return response()->json(['user' => $user], 200);
    }

    public function userSettings(Request $request)
    {
        $result = [
            "settings" =>  [
                "user" =>  [
                    "id" =>  $request->user()->id,
                    "name" =>  $request->user()->name,
                    "email" =>  $request->user()->email,
                    "fcm_token" =>  $request->user()->fcm_token,
                    "lang" =>  $request->user()->lang,
                ],
                "subjects" =>  [],
                "filtered_artists" =>  [],
                "accepted_lang" =>  [
                    [
                        "id" =>  "ru",
                        "name" =>  "РУ"
                    ],
                    [
                        "id" =>  "en",
                        "name" =>  "EN"
                    ]
                ],
                "links" =>  []
            ]
        ];
        return $result;
    }

    public function artworksList(Request $request)
    {
        $artworks = Artwork::with(['artist'])->where('status', 'accepted')->inRandomOrder()->limit(20)->get();
        //dd(($artworks->toArray())[0]['images']);
        //dd(($artworks->toArray()));
        $result = [];
        foreach ($artworks as $artwork) {
            $result[] = [
                'id' => $artwork->id,
                'title_en' => isset($artwork->title->en) ? $artwork->title->en : '',
                'title_ru' => isset($artwork->title->ru) ? $artwork->title->ru : '',
                'additional_info_en' => isset($artwork->description->en) ? $artwork->description->en : '',
                'additional_info_ru' => isset($artwork->description->ru) ? $artwork->description->ru : '',
                'file' => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
                'original_file' => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
                'watermark_file' => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
                'webpack_file' => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
                'wallpaper' => 0,
                'subject_id' => 0,
                'rate' => 0,
                'height' => 0,
                'width' => 0,
                'in_sale' => 0,
                "sale_price_rub" => null,
                "sale_price_usd" => null,
                "wear_merchandise" => 0,
                "size" => [
                    "width" => 0,
                    "height" =>  0
                ],
                "merchandise_prices" => [
                    "hoodie" => 99,
                    "tshirt" => 59
                ],
                "artist" => [
                    "id" => $artwork->artist->id,
                    "name_en" => isset($artwork->artist->fio->en) ? $artwork->artist->fio->en : '',
                    "name_ru" => isset($artwork->artist->fio->ru) ? $artwork->artist->fio->ru : '',
                    "url" => null,
                    "country" => (!in_array($artwork->artist->country, [null, '-'])) ? "https://arthall.online/storage/flags/" . mb_strtolower($artwork->artist->country) . ".svg" : "",
                    "merch_sale_agree" => 0
                ],
                "viewed" => []
            ];
        }
        return $result;
    }

    public function artworkDetail(Request $request, $id)
    {
        $artwork = Artwork::with(['artist'])->where('status', 'accepted')->findOrFail($id);

        list($width, $height, $type, $attr) = getimagesize(public_path($artwork->images[0]['url']));

        $result = [
            "id" => $artwork->id,
            "artist_id" => $artwork->artist->id,
            "subject_id" => 0,
            "title_en" => $artwork->title->en,
            "title_ru" => $artwork->title->ru,
            "additional_info_en" => $artwork->description->en,
            "additional_info_ru" => $artwork->description->ru,
            "file" => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
            "wallpaper" => 0,
            "rate" => 0,
            "in_sale" => 0,
            "sale_price_rub" => null,
            "sale_price_usd" => null,
            "wear_merchandise" => 0,
            "original_file" => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
            "watermark_file" => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
            "webpack_file" => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
            "size" => [
                "width" => $width,
                "height" => $height
            ],
            "merchandise_prices" => [
                "hoodie" => 99,
                "tshirt" => 59
            ],
            "artist" => [
                "id" => $artwork->artist->id,
                "name_en" => isset($artwork->artist->fio->en) ? $artwork->artist->fio->en : '',
                "name_ru" => isset($artwork->artist->fio->ru) ? $artwork->artist->fio->ru : '',
                "url" => null,
                "country" => (!in_array($artwork->artist->country, [null, '-'])) ? "https://arthall.online/storage/flags/" . mb_strtolower($artwork->artist->country) . ".svg" : "",
                "merch_sale_agree" => 0
            ],
            "subject" => [
                "id" => 0,
                "title_en" => "",
                "title_ru" => ""
            ],
            "width" => $width,
            "height" => $height
        ];
        return $result;
    }

    public function setArtworkViewed(Request $request, $id)
    {
        $result = [
            "painting_id" =>  $id,
            "user_id" =>  $request->user()->id,
            "updated_at" =>  "",
            "created_at" =>  "",
            "id" =>  0
        ];
        return $result;
    }

    public function setArtworkRate(Request $request, $id)
    {
        $result = [
            "painting_id" =>  $id,
            "user_id" =>  $request->user()->id,
            "updated_at" =>  "",
            "created_at" =>  "",
            "id" =>  0,
            "value" => ($request->rate >= 3) ? 'like' : 'dislike',
            "rate" => $request->rate
        ];
        return $result;
    }

    public function artistsList(Request $request)
    {
        $artists = Artist::with(['artworks'])->where('status', 'accepted')->whereNotNull('url')->inRandomOrder()->get();
        $result = [];
        foreach ($artists as $artist) {
            $result[] = [
                "id" => $artist->id,
                "name_en" => isset($artist->fio->en) ? $artist->fio->en : '',
                "name_ru" => isset($artist->fio->ru) ? $artist->fio->ru : '',
                "url" => isset($artist->url) ? $artist->url : '',
                "photo" =>  isset($artist->images[0]['url']) ? url($artist->images[0]['url']) : '',
                "country" => (!in_array($artist->country, [null, '-'])) ? "https://arthall.online/storage/flags/" . mb_strtolower($artist->country) . ".svg" : "",
                "merch_sale_agree" => 0
            ];
        }
        return $result;
    }

    public function artistDetail(Request $request, $id)
    {

        $artist = Artist::with(['artworks'])->where('status', 'accepted')->where('url',$id)->first();

        if (!$artist) $artist = Artist::with(['artworks'])->where('status', 'accepted')->findOrFail($id);

        $result = [
            "id" =>  $artist->id,
            "name_en" =>  isset($artist->fio->en) ? $artist->fio->en : '',
            "name_ru" =>  isset($artist->fio->ru) ? $artist->fio->ru : '',
            "about_en" =>  isset($artist->creative_concept->en) ? $artist->creative_concept->en : '',
            "about_ru" =>  isset($artist->creative_concept->ru) ? $artist->creative_concept->ru : '',
            "url" => null,
            "photo" =>  isset($artist->images[0]['url']) ? url($artist->images[0]['url']) : '',
            "country" => (!in_array($artist->country, [null, '-'])) ? "https://arthall.online/storage/flags/" . mb_strtolower($artist->country) . ".svg" : "",
            "merch_sale_agree" =>  0,
            "attitude" =>  []
        ];
        foreach ($artist->artworks as $artwork) {
            list($width, $height, $type, $attr) = getimagesize(public_path($artwork->images[0]['url']));

            $result['paintings'][] = [
                "id" => $artwork->id,
                "artist_id" => $artwork->artist->id,
                "subject_id" => 0,
                "title_en" => $artwork->title->en,
                "title_ru" => $artwork->title->ru,
                "file" => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
                "width" => $width,
                "height" => $height,
                "in_sale" => 0,
                "sale_price_rub" => null,
                "sale_price_usd" => null,
                "wear_merchandise" => 0,
                "original_file" => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
                "watermark_file" => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
                "webpack_file" => isset($artwork->images[0]['url']) ? url($artwork->images[0]['url']) : '',
                "size" => [
                    "width" => $width,
                    "height" => $height
                ],
                "merchandise_prices" => [
                    "hoodie" => 99,
                    "tshirt" => 59
                ],

            ];
        }
        return $result;
    }

    public function artistAttitude(Request $request, $id) {
        $result = [
            "user_id" => $request->user()->id,
            "artist_id" => $id,
            "updated_at" => "",
            "created_at" => "",
            "id" => 0,
            "attitude" => "neutral"
        ];

        return $result;
    }

    public function ownGalleries(Request $request)
    {
        $result = [
            "id" =>  0,
            "rate" =>  null,
            "title" =>  null,
            "url" =>  null,
            "halls" =>  [
                [
                    "id" => 0,
                    "user_gallery_id" => 0,
                    "title" => "Unnamed hall",
                    "size" => 6,
                    "capacity" => 6,
                    "paintings" => []
                ]
            ]
        ];
        return $result;
    }
}
