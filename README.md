
>Нужно стянуть все данные по описанным эндпоинтам и сохранить в БД.

###### Стек:

- Laravel 11.8
- MySQL 8.0.39
- PHP 8.2 и выше
- Docker 27.4.1
- Swagger 8.6.3

###### Установка проекта

1. Распаковывать архив в любую папку или же клонировать с помощью git clone


2. Установить зависимости

```bash
composer install
```

3. Для того, чтобы настроить файл окружения (.env), нужно переименовать файл .env.example в .env. Все настройки базы данных там написаны.

5. Генерация ключа приложения

```bash
php artisan key:generate
```

6. Данные для БД:

Сервер : sql.freedb.tech:3306
Пользователь : freedb_usual_user
Пароль : gKS5j$WgE47rXaJ
БД : freedb_task_manager_db

7. Выполняем миграции для создания таблиц в базе данных

```bash
php artisan migrate
```

8. Для запуска тестов

```bash
php artisan test
```

9. Генерация документации:

```bash
php artisan l5-swagger:generate
```
###### Она будет по адресу - http://your_local_api/api/documentation

###### Название единственной таблицы

- `tasks`

10. Собрать и запустить проект в докере

```bash
docker-compose up --build
```

11. Запустить миграции внутри контейнера

```bash
docker exec -it laravel_app bash
```

```bash
php artisan migrate
```