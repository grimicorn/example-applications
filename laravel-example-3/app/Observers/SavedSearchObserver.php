<?php

namespace App\Observers;

use App\SavedSearch;

class SavedSearchObserver
{
    /**
     * Listen to the SavedSearch deleting event.
     *
     * @param  SavedSearch  $search
     * @return void
     */
    public function deleting(SavedSearch $search)
    {
        $search->notifications->each->delete();
    }
}
