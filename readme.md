# Oxford Dictionary client

## DEV section

Run:

```bash
composer update --no-interaction --no-ansi
```

Create two files in your project root directory:

```bash
touch .env.local
touch .env.test
```

Add to these files data for connecting to the database and API settings:

```dotenv
### DATABASE
DATABASE_URL=mysql://USER_LOGIN:USER_PASS@127.0.0.1:3306/DB_NAME?serverVersion=5.7

### Oxford Dictionaries API
OXFORD_API_ID=API_ID
OXFORD_API_KEY=API_KEY
```

Create DB data:

```bash
php bin/console doctrine:database:create

### next command only for update
php bin/console doctrine:schema:update --force
```

Include test customer data:

```bash
php bin/console doctrine:fixtures:load
> yes
```

Customer auth test data:

```
log: user@mail.com
pas: secret
```
