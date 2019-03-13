<?php

Route::group(['middleware' => ['web']], function () {
    Route::resource('/future-letters',
                    'Buzkall\FutureLetters\FutureLetterController')
         ->except(['create', 'show']);
});
