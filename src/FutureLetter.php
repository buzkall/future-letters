<?php

namespace Buzkall\FutureLetters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

/**
 * Class FutureLetter
 *
 * @package Buzkall\FutureLetters
 * @mixin \Eloquent
 */
class FutureLetter extends Model
{
    use SoftDeletes;

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

    /**
     * Mutator to change the date format
     * @param $value
     */
    public function setSendingDateAttribute($value)
    {
        $this->attributes['sending_date'] = Carbon::createFromFormat('d/m/Y H:i', $value);
    }

    /**
     * Get letters assigned to a user with a cache
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

    /**
     * Used from cron function
     * @return mixed
     */
    public static function getFutureLettersToSend()
    {
        return self::where('sending_date', '<=', Carbon::now())
                   ->whereNull('sent_at')
                   ->get();
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    /**
     * Route notifications for the mail channel.
     *
     * @param  Notification  $notification
     * @return string
     */
    public function routeNotificationFor($notification)
    {
        return $this->email;
    }

}
