<?php

//No routes in here!
use App\Http\Controllers\Auth\AuthController;



Route::get('/db', function () {
    return 'wq111s3';
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('/mail', function () {
//    \Illuminate\Support\Facades\Mail::to('user@mail.ru')
//        ->send(new \App\Mail\TestMail());
});
