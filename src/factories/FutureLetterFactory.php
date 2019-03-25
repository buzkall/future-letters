<?php

use Faker\Generator as Faker;

$factory->define(\Buzkall\FutureLetters\FutureLetter::class, function (Faker $faker) {

    return [
        'user_id'      => function () {
            return factory(App\User::class)->create()->id;
        },
        'email'        => $faker->safeEmail,
        'subject'      => $faker->sentence,
        'message'      => $faker->text,
        'sending_date' => $faker->dateTimeBetween('+1 week', '+1 month')->format('d/m/Y H:i'),
    ];
});

$factory->state(\Buzkall\FutureLetters\FutureLetter::class, 'sent', function (Faker $faker) {
    return [
        'sending_date' => $faker->dateTimeBetween('-1 week', '-1 day')->format('d/m/Y H:i'),
        'sent_at'      => $faker->dateTimeBetween('0 day', '0 day'),
    ];
});
