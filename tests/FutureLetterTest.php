<?php

namespace Buzkall\FutureLetters\Tests;

use App\User;
use Buzkall\FutureLetters\FutureLetter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
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
        factory(FutureLetter::class, $count_not_sent)->create(['user_id'=> $user->id]);
        factory(FutureLetter::class, $count_sent)->states('sent')->create(['user_id'=> $user->id]);

        $this->assertCount($count_not_sent + $count_sent, FutureLetter::all());
    }

    public function testCreateFutureLetterSeveralUsers()
    {
        $count_user1 = 1;
        $user1 = factory(User::class)->create();
        Auth::login($user1);
        factory(FutureLetter::class, $count_user1)->create(['user_id'=> $user1->id]);

        $count_user2 = 1;
        $user2 = factory(User::class)->create();
        Auth::login($user2);
        factory(FutureLetter::class, $count_user2)->create(['user_id'=> $user2->id]);

        $this->assertCount($count_user1 + $count_user2, FutureLetter::all());
        $this->assertCount($count_user1, FutureLetter::getFutureLettersFromUserId($user1->id));
        $this->assertCount($count_user2, FutureLetter::getFutureLettersFromUserId($user2->id));
    }
}
