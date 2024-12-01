<?php

namespace App\Observers;

use App\Models\Artist;
use Illuminate\Support\Facades\Http;

class ArtistObserver
{
    private function sendTelegram($message) {
        $response = Http::get('https://api.telegram.org/bot5219393533:AAHPlGYKhMEMeEx7Ho0MFsyH5i8VXtX6los/sendMessage?chat_id=-1001704333037&text='.$message.'&disable_web_page_preview=true');
        return $response;
    }

    /**
     * Handle the Artist "created" event.
     */
    public function created(Artist $artist): void
    {
        $message = 'Создан новый художник! Источник: '.$artist->source.', имя: '.$artist->fio->ru;
        $this->sendTelegram($message);
    }

    /**
     * Handle the Artist "updated" event.
     */
    public function updated(Artist $artist): void
    {
        $changes = $artist->getDirty();
        if (isset($changes['status'])) {
            $message = 'Изменился статус художника! Имя: '.$artist->fio->ru.', источник: '.$artist->source.' новый статус: '.$changes['status'].'. Комментарий к статусу: '.$artist->status_comment;
            $result = $this->sendTelegram($message);
        }

    }

    /**
     * Handle the Artist "deleted" event.
     */
    public function deleted(Artist $artist): void
    {
        //
    }

    /**
     * Handle the Artist "restored" event.
     */
    public function restored(Artist $artist): void
    {
        //
    }

    /**
     * Handle the Artist "force deleted" event.
     */
    public function forceDeleted(Artist $artist): void
    {
        //
    }
}
