# Pet-project

### Стек

- PHP 8.1
- Laravel 10
- MySQL 8
- Nginx

### Сборка проекта

```
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Тесты
- Все тесты без остановок
  `php artisan test --testsuite=Feature`
- Все тесты с остановкой при возникновении ошибки
  `php artisan test --testsuite=Feature --stop-on-failure`

### PHPStan
- Уроверь ошибок - 6.
```
composer analyse
```
