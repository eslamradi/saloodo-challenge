<?php

namespace App\Listeners\Parcel;

use App\Events\Parcel\ParcelCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendParcelUpdateToBiker
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Parcel\ParcelCreated  $event
     * @return void
     */
    public function handle(ParcelCreated $event)
    {
        //
    }
}
