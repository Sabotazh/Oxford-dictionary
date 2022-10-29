# Oxford Dictionary client

## DEV section

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

Create DB data and make migration: 

```bash
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### Fixtures

Include test customer data (fixture):

```bash
php bin/console doctrine:fixtures:load -n
```

Customer auth user test data:

```
log: user@mail.com
pas: secret
```

Customer auth admin test data:

```
log: admin@mail.com
pas: secret
```

### After pull

```bash
composer update --no-interaction --no-ansi
yarn install
yarn encore dev
```

Update migrations:

```bash
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate -n
php bin/console doctrine:fixtures:load -n
```

or create make (`Makefile`) file and add next custom alias:

```makefile
migration\:refresh:
	php bin/console doctrine:database:drop --force
	php bin/console doctrine:database:create
	php bin/console doctrine:migrations:migrate -n
	php bin/console doctrine:fixtures:load -n
```

now just use command in console:

```
make migration:refresh
```

## Tests

Щоб було зручніше, на ріднесенької напишу.

Для дебагінка в тестах використовуйте функції `dd()` або `dump()`.

Якщо не виходить повністю протестувати щось, значить у вас помилка в архітектурі, декомпозуйте клас наприклад. Не тестуйте приватні або захищені методи!

### Unit

Юніт-тести **ТІЛЬКИ** для тестування якогось конкретного класу та його методів. Юніт-тест **НЕ** повинен лізти в якісь сервіси, лапати базу даних або смикати сторонні API.

Юніт-тести розміщені в директорії **tests/Unit**, подальша структура директорій згідно директорії **src**.

Клас тесту повинен мати імя класу, який тестується + постфікс `Test`. Метод тесту повинен починатися с префіксу `test`, подальше найменування згідно той логіки, яку ви бажаєте перевірити.

Помічайте метод тесту:

```php
/**
* @group unit
*/
```

Простий юніт-тест:

```php
use PHPUnit\Framework\TestCase;
use App\SomeClassName;

class SomeClassNameTest extends TestCase
{
    /**
     * @group unit
     */
    public function testSomeLogic(): void
    {
        $testClass = new SomeClassName();
        
        $result = $testClass->toArray();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }
}
```

Якщо ваш клас потребує заінжектити якийсь обєкт зі складною логікою ініціалізації, мокайте цей обєкт, мокайте метод, який ви будете викликати, мокайте результат, який повинен повернутися з цього обєкту. Як приклад, нам потрубен `UserRepository` для повноцінного тесту, робимо так:

```php
use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Repository\UserRepository;
use App\SomeClassName;

class SomeClassNameTest extends TestCase
{
    /**
     * @group unit
     */
    public function testSomeLogic(): void
    {
        $criteria = ['name' => 'User'];
    
        $userRepository = \Mockery::mock(UserRepository::class); // мокаємо наш репозиторій
        $userRepository
            ->shouldReceive('findOneBy') // який метод будемо викликати
            ->with($criteria) // які параметри будемо передавати в цей метод
            ->andReturn(new User()); // що цей метод поверне
            
        $testClass = new SomeClassName($userRepository); // інжектимо
        
        $result = $testClass->toArray(); // виконуємо логіку, яку требо протестувати
        
        $this->assertIsArray($result); // що повинно бути, щоб тест пройшов
        $this->assertNotEmpty($result); // що повинно бути, щоб тест пройшов
    }
}
```

Якщо мокнутий обєкт повинен кинути виняток, то робимо так:

```php
$userRepository = \Mockery::mock(UserRepository::class); // мокаємо наш репозіторій
$userRepository
    ->shouldReceive('findOneBy')
    ->with($criteria) 
    ->andThrowExceptions([new \Exception()]); // вказуємо який виняток ми будемо перехоплювати. МАСИВ!!!
    
$testClass = new SomeClassName($userRepository);

// А так ми перевіряємо, що виняток був
$this->expectException(Exception::class); // з початку це
$testClass->toArray();
```

### Functional

Тут ми тестуємо класи, які лізуть у сторонні сервіси, БД й тому подібне. Налоштуйте файл **.env.test**, щоб там були данні для підключення к БД так API.

> УВАГА! Симфоні додасть постфікс `_test` до імя вашої тестової БД, вона не грохне локальну БД!

Поперше, якщо потрібні данні з БД, налаштуйте тестове середовище:

```bash
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate -n --env=test
php bin/console doctrine:fixtures:load -n --env=test
```

Тепер у вас є тестова БД із тестовими даними.

Щоб оновити БД:

```bash
php bin/console doctrine:database:drop --force --env=test || true
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate -n --env=test
php bin/console doctrine:fixtures:load -n --env=test
```

І тепер ви можите викликати сторонні сервіси й працювати з ними натівно, наприклад репозіторій:

```php
$userRepository = static::getContainer()->get(UserRepository::class);
```

Якщо тести з реквестами, респонсами та інше, то наслідуються вони від `WebTestCase`. 

Для імітації веб-кліента:

```php
$client = static::createClient();
```

Якщо тестимо контролер, який дає рендер-респонс, то достатньо просто перевірити на:

```php
$this->assertResponseIsSuccessful();
```

Якщо роут секюрний, та необхідний аутентифіцірований юзер, через репозіторій отримуйте обєкт юзера (або по `id` через `find()`, або по роли, через `findByRole()`):

```php
$admin = $userRepository->findByRole('Admin')[0]; // індес 0 тут тому, що метод повертає масив юзерів, но нам достатньо любого
$client->loginUser($admin);
```

Ось приклад тесту контролера, який секюрний:

```php
namespace App\Tests\Functional\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{
    /**
     * @group functional
     */
    public function testRender()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $admin = $userRepository->findByRole('Admin')[0];
        $client->loginUser($admin);

        $client->request('GET', '/admin/dashboard');

        $this->assertResponseIsSuccessful();
    }
}
```

Зверніть увагу на:

```php
/**
* @group functional
*/
```

### Run tests

В залежності від потреб:

- запустити усі тести (довго, смикає API й т.п.)

```bash
php bin/phpunit --testdox
```

- запускаємо тільки юніт-тести або функціональні:

```bash
# unit tests
php bin/phpunit --testdox --group unit

# or fiunctional tests
php bin/phpunit --testdox --group functional
```

> Увага на префікс `unit` aбо `fiunctional`, воно потягне всі методи теста, які помічені `@group unit` (або `@group fiunctional`).

- запустити якісь конкретний тест (просто вказавши назву класу, без неймспейсу):

```bash
php bin/phpunit --testdox --filter DashboardControllerTest
```

- якщо налаштований **xdebug**, то можемо пройти тести та сформувати звіт покриття, який буде завантажений в директорію **coverage**:

```bash
php bin/phpunit --testdox --coverage-html coverage
```
