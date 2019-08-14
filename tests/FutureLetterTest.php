<?php

namespace Buzkall\FutureLetters\Tests;

use App\User;
use Buzkall\FutureLetters\FutureLetter;
use Buzkall\FutureLetters\FutureLetterController;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Auth;
use Tests\TestCase;

class FutureLetterTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testCreateFutureLetter()
    {
        $count_not_sent = 1;
        $count_sent = 1;

        $user = factory(User::class)->create();
        Auth::login($user);

        factory(FutureLetter::class, $count_not_sent)
            ->create(['user_id' => $user->id]);

        factory(FutureLetter::class, $count_sent)
            ->states('sent')
            ->create(['user_id' => $user->id]);

        $this->assertCount($count_not_sent + $count_sent, FutureLetter::all());
    }

    public function testCreateFutureLetterSeveralUsers()
    {
        $count_user1 = 1;
        $user1 = factory(User::class)->create();
        Auth::login($user1);
        factory(FutureLetter::class, $count_user1)->create(['user_id' => $user1->id]);

        $count_user2 = 1;
        $user2 = factory(User::class)->create();
        Auth::login($user2);
        factory(FutureLetter::class, $count_user2)->create(['user_id' => $user2->id]);

        $this->assertCount($count_user1 + $count_user2, FutureLetter::all());
        $this->assertCount($count_user1, FutureLetter::getFutureLettersFromUserId($user1->id));
        $this->assertCount($count_user2, FutureLetter::getFutureLettersFromUserId($user2->id));
    }

    public function testEditFutureLetter()
    {
        $count_not_sent = 1;
        $subject = 'Modified';

        $user = factory(User::class)->create();
        Auth::login($user);
        $future_letters = factory(FutureLetter::class, $count_not_sent)
            ->create(['user_id' => $user->id]);
        $future_letter = $future_letters[0];

        $this->assertNotEquals($subject, $future_letter->subject);
        $future_letter->subject = $subject;
        $future_letter->save();

        $modified_future_letters = FutureLetter::getFutureLettersFromUserId($user->id);

        $this->assertEquals($subject, $modified_future_letters[0]->subject);
    }


    public function testSendFutureLetterViaCronNotVerifiedGuest()
    {
        $count_not_sent = 1;
        $date = Carbon::yesterday()->format('d/m/Y H:i');

        $future_letters = factory(FutureLetter::class, $count_not_sent)
            ->create(['user_id'      => NULL,
                      'sending_date' => $date]);

        $future_letter = $future_letters[0];
        $this->assertNull($future_letter->sent_at);

        // fake cron
        $future_letter = new FutureLetterController();
        $future_letter->cron();

        $user_future_letters = FutureLetter::all();
        $this->assertNull($user_future_letters[0]->sent_at);
    }

    public function testSendFutureLetterViaCronNotVerifiedButLogged()
    {
        $count_not_sent = 1;
        $date = Carbon::yesterday()->format('d/m/Y H:i');

        $user = factory(User::class)->create();
        Auth::login($user);

        $future_letters = factory(FutureLetter::class, $count_not_sent)
            ->create(['user_id'      => $user->id,
                      'sending_date' => $date]);

        $future_letter = $future_letters[0];
        $this->assertNull($future_letter->sent_at);

        // fake cron
        $future_letter = new FutureLetterController();
        $future_letter->cron();

        $user_future_letters = FutureLetter::getFutureLettersFromUserId($user->id);
        $this->assertNotNull($user_future_letters[0]->sent_at);
    }

    public function testSendFutureLetterViaCron()
    {
        $count_not_sent = 1;
        $date = Carbon::yesterday()->format('d/m/Y H:i');

        $user = factory(User::class)->create();
        Auth::login($user);
        $future_letters = factory(FutureLetter::class, $count_not_sent)
            ->states('verified')
            ->create(['user_id'      => $user->id,
                      'sending_date' => $date]);
        $future_letter = $future_letters[0];
        $this->assertNull($future_letter->sent_at);

        // fake cron
        $future_letter = new FutureLetterController();
        $future_letter->cron();

        $user_future_letters = FutureLetter::getFutureLettersFromUserId($user->id);
        $this->assertNotNull($user_future_letters[0]->sent_at);
    }
}
