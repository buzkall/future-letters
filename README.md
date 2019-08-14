# Future Letters

Laravel package used to write letters to you future self, that will be delivered to your inbox on a selected date.
To avoid spam, a verification email (only one per day) will be sent when sending as guest. 

## Installation

1. Install via composer:
    ```
    composer require buzkall/future-letters
    ```

2. Publish files

    ```
    php artisan vendor:publish --provider='Buzkall\FutureLetters\FutureLettersServiceProvider'
    ```

3. After configuring your local database in the .env file, run 

    ```
    php artisan make:auth
    php artisan migrate
    ```

4. Register a user, log in and navigate to /future-letters

<br/>

You can seed the database with faker data running:
```
php artisan db:seed --class=Buzkall\\FutureLetters\\FutureLetterSee
```

<br/>
You can modify the views which have been copied to the views directory then publishing

<p align="center">
    <img src="https://i.imgur.com/akuneKQr.png" alt="teachable_schema">
</p>

## Demo
You can use the demo in https://futureletters.aliciarodriguez.me/future-letters
