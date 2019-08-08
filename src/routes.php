<?php

// override the default / route
Route::get('/', function () {
    return redirect('future-letters');
});

Route::group(['middleware' => ['web']], function () {
    Route::get('/future-letters',
               'Buzkall\FutureLetters\FutureLetterController@index')
         ->name('future-letters.index');

    Route::post('/future-letters',
                'Buzkall\FutureLetters\FutureLetterController@store');

    Route::get('/future-letters/cron', 'Buzkall\FutureLetters\FutureLetterController@cron')
         ->name('future-letters.cron');
});


Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/future-letters/{id}/edit',
               'Buzkall\FutureLetters\FutureLetterController@edit')
         ->name('future-letters.edit');
    Route::put('/future-letters/{id}',
               'Buzkall\FutureLetters\FutureLetterController@update');
    Route::delete('/future-letters/{id}',
                  'Buzkall\FutureLetters\FutureLetterController@destroy');
});
