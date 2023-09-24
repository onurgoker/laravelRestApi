# Laravel Rest API v1.0.0

Developer notes: 
PSR standarts are applied with respect to SonarLint tool configuration.
Some unit tests are cli execution commands are described for testing purposes to demonstrate the functionality.
This project does not fully support all unit tests because of limited time afford.

Suggested IDE / Text Editor: Visual Studio Code

Laravel Version: v10.X
Used libraries:
- Laravel Sanctum for application security
- Laravel Tinker for cli execution support
- Laravel Test for unit tests

Created by: Onur GÖKER

Created at: 2023-09-24

## Requirements

- PHP 8.X or higher
- MySQL or MariaDB 10.4 or higher
- Apache or nginx server or local Laravel server
## Runing Application

- Just fork the repository and configure your environment accordingly.
- Run the following command: 

~~~
composer install
~~~

- Clone from .env.example and update the environment file (.env) with your local configuration.
- Execute the following script in your project's root directory.

~~~
php artisan migrate
~~~

Run the following command and execute the laravel application

~~~
php artisan serve
~~~

Sample Postman collection is available in root directory!
~~~
https://github.com/onurgoker/laravelRestApi/blob/main/Laravel%20Rest%20API.postman_collection.json
~~~

## Running Unit Tests

To run unit tests just execute the following command in project root directory:

~~~
php artisan test
~~~

## Running Application via CLI

Laravel tinker will provide execution of the application via CLI. Execute the following command in your project's root directory:

~~~
php artisan tinker
~~~

Now one can run the application via CLI. Sample commands:

List all the users:

~~~
App\Models\User::all();
~~~

Create a new user:

~~~
$user = new App\Models\User;
$user->name = "testUser";
$user->email = "testUser@usertest.com";
$user->password = bcrypt("temp123");
$user->save();
~~~

Delete subscription (dont forget to update available subscription id):

~~~
$controller = app()->make('App\Http\Controllers\SubscriptionController');
app()->call([$controller,'delete'], ['id' => 1]);
~~~

List all transactions:

~~~
App\Models\Transaction::all();
~~~

List all subscriptions:

~~~
App\Models\Subscription::all();
~~~
