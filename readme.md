## Twitch bot

### Getting started

#### Setup your Twitch application

[http://www.twitch.tv/kraken/oauth2/clients/new](http://www.twitch.tv/kraken/oauth2/clients/new)

Enter the client ID, secret, and redirect URI in ```config/twitch.php```

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
php artisan chat 0 # public
php artisan chat 1 # private
```

#### Register your twitch account

[http://localhost:8000/register](http://localhost:8000/register)

#### Start chatting!

### Keywords

Keywords are located in ```app/Keywords```. The file and class name should named the same as the keyword. The
```handle``` method is responsible for receiving the keyword's parameters, handling them accordingly and responding
via ```$this->chatter```, if necessary.