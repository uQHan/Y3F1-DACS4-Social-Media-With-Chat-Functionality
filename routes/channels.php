<?php

use App\Events\MyEvent;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chatgroup.{id}', function ($user, $id) {
    return $user->groups->contains('id', $id);
});