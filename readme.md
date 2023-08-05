# Окружение разработчика

## Подготовка

1. Установить Docker:

    * [Docker](https://docs.docker.com/engine/install/)
    * [Docker-compose](https://docs.docker.com/compose/install/)
    
2. Склонировать репозиторий:

## Первый запуск

1. Установить  зависимости

    `docker-compose run --rm web composer install`

2. Запустить контейнеры

    `docker-compose up -d`

3. Развернуть dev-окружение

    `docker-compose exec web ./init --env=Development --overwrite`    

4. Применить последние миграции

    `docker-compose exec web ./yii migrate`

5. Открыть сайт в браузере:

    Admin: [localhost:9020/adminx42vcx](http://localhost:9020/adminx42vcx)   

    Front: [localhost:9020](http://localhost:9020)
    
    MySQL: localhost:3420

    Mailhog: [localhost:8420](http://localhost:8420)


## Второй запуск

1. Запустить контейнеры

    `docker-compose up -d`

1. Открыть сайт в браузере
    
### Тестовые пользователи

- root@dev.loc 12345678
- admin@dev.loc 12345678
- organizer@dev.loc 12345678
### Права доступа к файлам
Для загрузки файлов рекурсивно укажите права 777 на директорию project

