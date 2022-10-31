# Oxford Dictionary client

## Install

Clone app:

```bash
git clone git@github.com:Sabotazh/Oxford-dictionary.git
```

Setup **.env** file:

- setup your DB:

```dotenv
DATABASE_URL=mysql://USER_LOGIN:USER_PASS@127.0.0.1:3306/DB_NAME?serverVersion=5.7
```

- setup [Oxford Dictionaries API](https://developer.oxforddictionaries.com/)

```dotenv
OXFORD_API_ID=API_ID
OXFORD_API_KEY=API_KEY
```

Run command:

```bash
composer app-init

# or

composer install
yarn install
yarn encore dev
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate -n
php bin/console doctrine:fixtures:load -n
```

Run symfony server:

```bash
composer app-server

# or

symfony server:start
```

The project will start at [http://127.0.0.1:8000](http://127.0.0.1:8000).

You can login as user using test data:

```
log: user@mail.com
pas: secret
```

or as Admin:

```
log: admin@mail.com
pas: secret
```

## Tests

![img](doc/images/test-coverage.png)

Create **.env.test** file and setup it:

```dotenv
DATABASE_URL=mysql://USER_LOGIN:USER_PASS@127.0.0.1:3306/DB_NAME?serverVersion=5.7
OXFORD_API_ID=API_ID
OXFORD_API_KEY=API_KEY
```

Run command:

```bash
composer app-test-init

# or 

php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate -n --env=test
php bin/console doctrine:fixtures:load -n --env=test
```

then run tests:

```bash
composer app-test
```

You can start specific tests:

```bash
# only unit tests
composer app-test-unit

# only functional tests
composer app-test-functional

# all tests with coverage
composer app-test-coverage
```
