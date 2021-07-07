<?php

namespace App\Observers;

use App\Models\Catalog;

class CatalogObserver
{
    /**
     * Handle the Catalog "created" event.
     *
     * @param Catalog $catalog
     * @return void
     */
    public function created(Catalog $catalog)
    {
        //
    }

    /**
     * Handle the Catalog "updated" event.
     *
     * @param Catalog $catalog
     * @return void
     */
    public function updated(Catalog $catalog)
    {
        //
    }

    /**
     * Handle the Catalog "deleted" event.
     *
     * @param Catalog $catalog
     * @return void
     */
    public function deleted(Catalog $catalog)
    {

    }

    /**
     * Handle the Catalog "restored" event.
     *
     * @param Catalog $catalog
     * @return void
     */
    public function restored(Catalog $catalog)
    {
        //
    }

    /**
     * Handle the Catalog "force deleted" event.
     *
     * @param Catalog $catalog
     * @return void
     */
    public function forceDeleted(Catalog $catalog)
    {
        $catalog->products()->detach();
        // TODO Очищать картинки продуктов перед тем как удалять
    }
}
