<?php

use Illuminate\Support\Facades\Broadcast;



/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::routes(['middleware' => ['auth']]);



Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}); 



Broadcast::channel('instructor.{id}', function ($user, $id) {
    \Log::info('Channel Auth', ['user_id' => optional($user)->id, 'expected_id' => $id]);
    return (int) $user->id === (int) $id;
});
