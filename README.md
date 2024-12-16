Install
=======

Clone this repository

```bash
$ git clone git@github.com:adrienroches-sensio/sf7pack_2024.12.16.git
$ cd ./sf7pack_2024.12.16
```

Run the following commands :

```bash
$ docker compose up -d # Creates a postgres container or have one already accessible by this application
$ symfony composer install
$ symfony console importmap:install
$ symfony console doctrine:database:create --if-not-exists
$ symfony console doctrine:migration:migrate --no-interaction
$ symfony console doctrine:fixtures:load --no-interaction
```

Then start the server with :

```bash
$ symfony serve -d
```

Then you can log in with :
* admin@example.com / admin [ROLE_ADMIN]

Discover which routes exists :

```bash
$ symfony console debug:router --show-controllers
```
