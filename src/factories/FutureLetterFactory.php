<?php

use Buzkall\FutureLetters\FutureLetter;
use Faker\Generator as Faker;

$factory->define(FutureLetter::class, function (Faker $faker) {
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

$factory->state(FutureLetter::class, 'sent', function (Faker $faker) {
    return [
        'sending_date' => $faker->dateTimeBetween('-1 week', '-1 day')->format('d/m/Y H:i'),
        'sent_at'      => $faker->dateTimeBetween('0 day', '0 day'),
    ];
});

$factory->state(FutureLetter::class, 'deleted', function (Faker $faker) {
    return [
        'sending_date' => $faker->dateTimeBetween('+1 week', '+2 week')->format('d/m/Y H:i'),
        'deleted_at'   => $faker->dateTimeBetween('0 day', '0 day'),
    ];
});
