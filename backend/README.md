Требуемая версия PHP 8.0
```
cp .env.example .env
```
Заполните поля
DB_PASSWORD
и
APP_URL
в созданном .env

```
composer i
```
Далее необходимо настроить базу данных, и хост. Или можно воспользоваться Docker
```
./vendor/bin/sail up -d
```
Далее
```
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
php artisan apikey:generate app1
```
В консоли можно будет увидеть созданный ключ, копируем его, далее будем его называть API_KEY

С помощью последней команды можно создать несколько ключей (но если это не продакшн, можно обойтись одним ключем)

Если что, список активных ключей можно посмотреть тут
```
php artisan apikey:list -D
```
Если что, ключ можно удалить командой
```
php artisan apikey:delete app1
```
На главной странице сайта можно увидеть документацию по API