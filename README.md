# TechZone — интернет-магазин на Laravel 12

Проект содержит публичный каталог, поиск и фильтры, карточки товаров, гостевую и пользовательскую корзину, промокоды, оформление заказов, личный кабинет, отзывы и административный раздел.

## Требования

- PHP 8.2+ с расширениями mbstring, PDO, GD и поддержкой WebP;
- Composer;
- MySQL 8+ или MariaDB;
- веб-сервер с корнем сайта, направленным в каталог public.

## Первое развёртывание

    cp .env.example .env
    composer install --no-dev --optimize-autoloader
    php artisan key:generate
    php artisan migrate --force
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

Каталоги storage и bootstrap/cache должны быть доступны на запись пользователю веб-сервера.

## Обязательные настройки

В .env заполните:

- APP_URL — реальный HTTPS-домен;
- подключение DB_*;
- рабочую отправку почты MAIL_*;
- STORE_PHONE, STORE_EMAIL, STORE_ADDRESS, STORE_HOURS;
- STORE_LEGAL_NAME, STORE_INN, STORE_OGRN, STORE_LEGAL_ADDRESS;
- STORE_RETURN_ADDRESS и при необходимости STORE_BANK_DETAILS;
- STORE_ADMIN_EMAILS — email администраторов через запятую;
- YANDEX_METRIKA_ID — необязательно; аналитика загрузится только после согласия пользователя.

Не включайте APP_DEBUG на production.

## Административный доступ

Зарегистрируйте обычный аккаунт с email, указанным в STORE_ADMIN_EMAILS, затем откройте /admin. Также поддерживается поле users.is_admin.

В административном разделе можно:

- редактировать цену, остаток, описание, характеристики и SEO товара;
- загружать фотографии — они преобразуются в WebP 1200×1200;
- менять статусы заказов и оплаты;
- публиковать и удалять отзывы.

Для безопасной проверки преобразования уже существующих локальных фото:

    php artisan products:normalize-images --dry-run

После резервной копии БД выполните команду без флага dry-run. Внешние URL и отсутствующие файлы будут пропущены.

## Обновление сервера

    git pull --ff-only origin codex/fix-missing-images-in-store
    composer install --no-dev --optimize-autoloader
    php artisan migrate --force
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

Перед обновлением сделайте резервную копию БД и пользовательских изображений.

Команда `db:seed` по умолчанию ничего не добавляет. Старые демонстрационные сидеры разрешены только локально при явном `SEED_DEMO_DATA=true`; на production включать этот параметр нельзя.

## Проверка

    php artisan test
    php artisan route:list
    php artisan config:show store

После выкладки вручную проверьте каталог, товар с вариантом, корзину гостя, вход с сохранением корзины, промокод, оформление заказа, письмо, личный кабинет и административный статус заказа.
