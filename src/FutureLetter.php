<?php

namespace Buzkall\FutureLetters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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


    public static function boot()
    {
        parent::boot();

        if (auth()->user()) {
            static::creating(function ($model) {
                $model->user_id = auth()->user()->id;
            });

            static::saving(function ($model) {
                Cache::forget('getFutureLettersFromUserId'.  auth()->user()->id);
            });
        }
    }

    public function setSendingDateAttribute($value)
    {
        $this->attributes['sending_date'] = Carbon::createFromFormat('d/m/Y H:i', $value);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public static function getFutureLettersFromUserId($user_id)
    {
        $query = self::where('user_id', $user_id)
                     ->orderBy('sending_date', 'ASC');
        $name = 'getFutureLettersFromUserId' . $user_id;
        $time = 600;
        return Cache::remember($name, $time, function () use ($query) {
            return $query->get();
        });
    }

    public static function getFutureLettersToSend()
    {
        return self::where('sending_date', '<=', Carbon::now())
                   ->whereNull('sent_at')
                   ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
