<?php

namespace Buzkall\FutureLetters;

use Illuminate\Database\Seeder;

class FutureLetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(FutureLetter::class, 2)->create();
        factory(FutureLetter::class, 2)->state('sent')->create();
    }
}
