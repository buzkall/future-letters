<?php

namespace Buzkall\FutureLetters;

use Auth;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Cache;

/**
 * Class FutureLetter
 *
 * @package Buzkall\FutureLetters
 * @mixin Eloquent
 */
class FutureLetter extends Model
{
    use SoftDeletes;
    use Notifiable;
    use MustVerifyEmail;

    protected $fillable = [
        'user_id', 'email', 'subject', 'message', 'sending_date', 'email_verified_at', 'sent_at',
    ];

    protected $dates = ['sending_date', 'email_verified_at', 'sent_at'];


    public static function boot()
    {
        parent::boot();

        if (auth()->user()) {
            static::creating(function ($model) {
                $model->user_id = auth()->user()->id;
            });

            static::saving(function ($model) {
                Cache::forget('getFutureLettersFromUserId' . auth()->user()->id);
            });
        }
    }

    /**
     * Mutator to change the date format
     *
     * @param $value
     */
    public function setSendingDateAttribute($value)
    {
        $this->attributes['sending_date'] = Carbon::createFromFormat('d/m/Y H:i', $value);
    }

    /**
     * Get letters assigned to a user with a cache
     *
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
     * Used from cron function - send only the lines with a date in verified_at and without sent_at
     *
     * @return mixed
     */
    public static function getFutureLettersToSend()
    {
        return self::where('sending_date', '<=', Carbon::now())
                   ->where(function ($query) {
                       $query->whereNotNull('email_verified_at')
                             ->orWhereNotNull('user_id');
                   })
                   ->whereNull('sent_at')
                   ->get();
    }

    public static function getNumberOfUnverifiedEmailsSentToEmail($email, $days = 1)
    {
        return self::where('email', $email)
                   ->whereNull('email_verified_at')
                   ->whereDate('created_at', '>', Carbon::now()->subDays($days))
                   ->count();
    }

    public function userIsOwner()
    {
        if (is_null($this->user) && !Auth::guest()) {
            $this->assignLetterToUser();
        }
        return $this->user_id === Auth::id();
    }

    public function assignLetterToUser()
    {
        $this->update(['user_id' => Auth::user()->id]);
    }

    public function markEmailAsVerified()
    {
        $this->update(['email_verified_at' => Carbon::now()]);
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
     * @param Notification $notification
     * @return string
     */
    public function routeNotificationFor($notification)
    {
        return $this->email;
    }

}
