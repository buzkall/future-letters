<?php

namespace Buzkall\FutureLetters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FutureLetter
 *
 * @package Buzkall\FutureLetters
 */
class FutureLetter extends Model
{
    protected $fillable = [
        'email', 'subject', 'message', 'sending_date', 'sent_at',
    ];

    protected $dates = ['sending_date', 'sent_at'];

    public function setSendingDateAttribute($value)
    {
        $this->attributes['sending_date'] = Carbon::createFromFormat('d/m/Y H:i', $value);
    }

    public static function getFutureLetters()
    {
        return self::orderBy('sending_date', 'ASC')->get();
    }

}
