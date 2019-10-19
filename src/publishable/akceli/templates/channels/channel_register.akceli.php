Broadcast::channel('App.[[Channel]].{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});
