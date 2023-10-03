<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*44444444444444444444444444444444444444444444
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('mapping.{id}', function () {
    return Auth::check();
});

Broadcast::channel('user.{id}', function () {
    return Auth::check();
});

Broadcast::channel('char.{id}', function () {
    return Auth::check();
});

Broadcast::channel('online', function () {
    return Auth::check();
});

Broadcast::channel('allthestats', function () {
    return Auth::check();
});

Broadcast::channel('usertracking.{id}', function () {
    return Auth::check();
});

Broadcast::channel('hotarea', function () {
    return Auth::check();
});

Broadcast::channel('route.{id}', function () {
    return Auth::check();
});

Broadcast::channel('whalers', function () {
    return Auth::check();
});

Broadcast::channel('charts', function () {
    return Auth::check();
});

Broadcast::channel('charlogs', function () {
    return Auth::check();
});

Broadcast::channel('connectionnotes.{id}', function () {
    return Auth::check();
});

Broadcast::channel('allconnections', function () {
    return Auth::check();
});

Broadcast::channel('scopeh', function () {
    return Auth::check();
});

Broadcast::channel('allthestatsuser.{id}', function () {
    return Auth::check();
});

Broadcast::channel('sigs', function () {
    return Auth::check();
});

Broadcast::channel('sigsp', function () {
    return Auth::check();
});


Broadcast::channel('test', function () {
    return Auth::check();
});

Broadcast::channel('shortest', function () {
    return Auth::check();
});

Broadcast::channel('broken', function () {
    return Auth::check();
});

Broadcast::channel('static', function () {
    return Auth::check();
});

Broadcast::channel('room.{id}', function () {
    return Auth::check();
});

Broadcast::channel('rooms', function () {
    return Auth::check();
});

  /////---------------------/////

//  Broadcast::channel('App.User.{id}', function ($user, $id) {
//     return true;
// });

// Broadcast::channel('notes', function () {
//     return true;
//   });

//   Broadcast::channel('campaigns', function () {
//     return true;
//   });

//   Broadcast::channel('campaignsystem.{id}', function () {
//     return true;
//   });

//   Broadcast::channel('userupdate', function () {
//     return true;
//   });

//   Broadcast::channel('campaignsystemmembers.{id}', function () {
//     return true;
//   });

//   Broadcast::channel('stations', function () {
//     return true;
//   });

//   Broadcast::channel('towers', function () {
//     return true;
//   });
