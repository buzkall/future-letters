<?php

namespace Buzkall\FutureLetters;

use Illuminate\Database\Eloquent\Model;

class FutureLetter extends Model
{
    protected $table = 'future_letters';

    protected $fillable = [
        'email', 'subject', 'message', 'sending_date', 'sent_at',
    ];

    protected $dates = ['sending_date', 'sent_at'];
}
