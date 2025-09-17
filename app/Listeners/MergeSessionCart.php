<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\CartController;

class MergeSessionCart implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        // The CartController has the logic; instantiate and call merge.
        // We pass the current request instance via app()->make so session is available.
        $controller = app()->make(CartController::class);
        $request = app('request');

        // call mergeSessionIntoDatabase with explicit user id
        $controller->mergeSessionIntoDatabase($request, $event->user->id);
    }
}
