<?php

namespace App\Listeners\Parcel;

use App\Events\Parcel\ParcelDilevered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendParcelUpdateToCustomer
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
     * @param  \App\Events\Parcel\ParcelDilevered  $event
     * @return void
     */
    public function handle(ParcelDilevered $event)
    {
        //
    }
}
