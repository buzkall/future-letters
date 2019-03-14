<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::resource('/future-letters',
                    'Buzkall\FutureLetters\FutureLetterController')
         ->except(['create', 'show']);

});
