<?php

namespace App\Observers;

use App\Models\Appraising;

class AppraisingObserver
{
    /**
     * Handle the Appraising "created" event.
     *
     * @param  \App\Models\Appraising  $appraising
     * @return void
     */
    public function created(Appraising $appraising)
    {
        //
    }

    /**
     * Handle the Appraising "updated" event.
     *
     * @param  \App\Models\Appraising  $appraising
     * @return void
     */
    public function updated(Appraising $appraising)
    {
        //
    }

    /**
     * Handle the Appraising "deleted" event.
     *
     * @param  \App\Models\Appraising  $appraising
     * @return void
     */
    public function deleted(Appraising $appraising)
    {
        //
    }

    /**
     * Handle the Appraising "restored" event.
     *
     * @param  \App\Models\Appraising  $appraising
     * @return void
     */
    public function restored(Appraising $appraising)
    {
        //
    }

    /**
     * Handle the Appraising "force deleted" event.
     *
     * @param  \App\Models\Appraising  $appraising
     * @return void
     */
    public function forceDeleted(Appraising $appraising)
    {
        $appraising->clearFile();
    }
}
