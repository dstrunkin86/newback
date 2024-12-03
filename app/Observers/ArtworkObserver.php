<?php

namespace App\Observers;

use App\Models\Artwork;
use Illuminate\Support\Facades\Http;

class ArtworkObserver
{
    private function sendTelegram($message) {
        $response = Http::get('https://api.telegram.org/bot5219393533:AAHPlGYKhMEMeEx7Ho0MFsyH5i8VXtX6los/sendMessage?chat_id=-1001704333037&text='.$message.'&disable_web_page_preview=true');
        return $response;
    }

    /**
     * Handle the Artwork "created" event.
     */
    public function created(Artwork $artwork): void
    {
        //
    }

    /**
     * Handle the Artwork "updated" event.
     */
    public function updated(Artwork $artwork): void
    {
        $changes = $artwork->getDirty();
        if (isset($changes['status'])) {
            $message = 'Изменился статус работы! Название: '.$artwork->title->ru.', новый статус: '.$changes['status'].'. Комментарий к статусу: '.$artwork->status_comment;
            $result = $this->sendTelegram($message);
        }
    }

    /**
     * Handle the Artwork "deleted" event.
     */
    public function deleted(Artwork $artwork): void
    {
        $artwork->tags()->detach();
        $artwork->compilations()->detach();
    }

    /**
     * Handle the Artwork "restored" event.
     */
    public function restored(Artwork $artwork): void
    {
        //
    }

    /**
     * Handle the Artwork "force deleted" event.
     */
    public function forceDeleted(Artwork $artwork): void
    {
        //
    }
}
