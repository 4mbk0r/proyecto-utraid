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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('saludo-usuario.{userId}', function ($user, $userId) {
    // En este ejemplo, permitimos que el usuario acceda al canal si está autenticado y el userId coincide con el suyo.
    return $user->id === (int) $userId && auth()->check();
});