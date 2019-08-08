# Future Letters

Laravel package used to write letters to you future self, that will be delivered to your inbox on a selected date.

## Installation

Install via composer:

```
composer require buzkall/future-letters
```

Run migrations, register a user, log in and go to /future-letters

<br/>

You can seed the database with faker data running:
```
php artisan db:seed --class=Buzkall\\FutureLetters\\FutureLetterSee
```

<br/>
You can modify the views publishing them:

```
php artisan vendor:publish
```


<p align="center">
    <img src="https://i.imgur.com/akuneKQr.png" alt="teachable_schema">
</p>
