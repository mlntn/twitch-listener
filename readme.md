## Twitch bot

### Getting started

#### Initialize the DB
```bash
touch storage/database.sqlite
php artisan migrate
```

#### Start the web server
```bash
php artisan serve
```

#### Start the background public and private chat listeners
```bash
php artisan chat 0
php artisan chat 1
```

#### Register your twitch account

[http://localhost:8000/register]()