## Item Management Assignment

I have developed item management assignment using Laravel and ReactJs with MySQL DB.

## Laravel

Laravel Framework : 8.10.0 (https://laravel.com/docs)

## System Requirement

- **PHP >= 7.3
- **BCMath PHP Extension
- **Ctype PHP Extension
- **Fileinfo PHP Extension
- **JSON PHP Extension
- **Mbstring PHP Extension
- **OpenSSL PHP Extension
- **PDO PHP Extension
- **Tokenizer PHP Extension
- **XML PHP Extension

## Installation

STEP 1: Clone the repository using below command.

	(or) 

	download the repository in zip and extract in your server.

STEP 2: On command promp, move to "public_html" folder and run 		"composer update".

STEP 3: Open "public_html/.env" file and update your url and DB credentials.

STEP 4: Create DB and import sql dump file within the folder 	"sql".
     (or)
     On command promp, move to "public_html" folder and run 		"php artisan migrate".
STEP 5: (optional) run "php artisan db:seed --class=ItemSeeder" 	to load dummy data

STEP 6: Finally, run "php artisan serve" and open the given URL in your browser

