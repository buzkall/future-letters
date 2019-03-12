<?php

use Faker\Generator as Faker;

$factory->define(\Buzkall\FutureLetters\FutureLetter::class, function (Faker $faker) {
    return [
        'email'        => $faker->safeEmail,
        'subject'      => $faker->sentence,
        'message'      => $faker->text,
        'sending_date' => $faker->dateTimeBetween('+1 week', '+1 month'),
    ];
});

$factory->state(\Buzkall\FutureLetters\FutureLetter::class, 'sent', function (Faker $faker) {
    return [
        'sending_date' => $faker->dateTimeBetween('-1 week', '-1 day'),
        'sent_at'      => $faker->dateTimeBetween('0 day', '0 day'),
    ];
});
