## Get Start
Use `git clone`
If not have a `.env` file

    cp .env.example .env 

Up all containers

    ./vendor/bin/sail up

Install PHP deps

    ./vendor/bin/sail composer install

___

    ./vendor/bin/sail php artisan key:generate
    ./vendor/bin/sail npm ci || ./vendor/bin/sail npm install
    ./vendor/bin/sail npm run dev
