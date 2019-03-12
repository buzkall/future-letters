<?php

namespace Buzkall\FutureLetters\Tests;

use Buzkall\FutureLetters\FutureLetter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FutureLetterTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testCreateFutureLetter()
    {
        $count_not_sent = 1;
        $count_sent = 1;
        factory(FutureLetter::class, $count_not_sent)->create();
        factory(FutureLetter::class, $count_sent)->states('sent')->create();

        $this->assertCount($count_not_sent + $count_sent, FutureLetter::all());
    }
}
