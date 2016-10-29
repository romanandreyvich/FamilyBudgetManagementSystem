Family budget management system
======================== 

В рамках выполнения тестового задания была реализована система ведения семейного бюджета. Выполнена в соответствие с [техническим заданием]

Серверная часть разработана на основе следующего стека технологий:

> php 7.0, Symfony 3.0, Doctrine 2, MySQL 5.7

Клиентская часть:

> Angular 1.5.6, Twitter Bootstrap


Установка
--------------

**1. Клон проекта**

```
git clone git@github.com:romanandreyvich/FamilyBudgetManagementSystem.git
```

Команда создаст директорию FamilyBudgetManagementSystem/ и копию проекта внутри директории, 
если нет необходимости создавать директорию FamilyBudgetManagementSystem/ то после команды нужно
добавить точку ".", чтобы копия проекта появилась в той директории из которой выполняется команда.

**2. Установка зависимостей проекта через composer**

В корневой директории проекта (помолочанию FamilyBudgetManagementSystem/) необходимо выполнить
команду:

```
curl -s http://getcomposer.org/installer | php
php composer.phar install -o
```
Данная команда выполнит загрузку всех необходимых проекту зависимостей, создаст "app/config/parameters.yml" файл,
предложит ввести данные для соединения с базой, очистит кэш.

**3. Создание базы данных и схемы**

Вы должны создать базу данных для проекта и прописать настройки соединения с 
БД в файл app/config/parameters.yml (если не сделали этого во время пункта 2)

Базу данных можно создать следующей командой (если Вы прописали настройки в файле app/config/parameters.yml либо во время команды php composer.phar install -o):

```
php bin/console doctrine:database:create
```

Затем необходимо выполнить команду в корневой директории проекта:

```
php bin/console doctrine:schema:update --force
```

Данная команда обновит схему бд, создаст необходимые таблицы.

Для установки тестовых данных, необходимо выполнить следующую команду в корневой директории проекта

```
php bin/console fixtures:test
```

**3. Assets install**

Следующая команда создаст symlink файлов и директорий из директории пакета (AppBundle/Resources/public/)
Выполнить ее необходимо в корневой директории проекта
```
php bin/console assets:install --symlink
```

**4. установка прав записи на папки var/cache и var/logs**

Для установки прав доступа на директорий var/cache и var/logs необходимо 
выполнить данные команды в корневой директории проекта.

Для Mac OS X

```
$ rm -rf var/cache/*
$ rm -rf var/logs/*

$ HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
$ sudo chmod -R +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" var
$ sudo chmod -R +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" var
```

Для Linux/BSD

```
$ HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
# if this doesn't work, try adding `-n` option
$ sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
$ sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
```

**5. Проверить системные требования**

Чтобы убедиться, что проект будет работать нормально, необходимо проверить Вашу систему
на соответствие системным требованиям. 
Сделать это можно, выполнив команду следующую из командной строки:

```
php bin/symfony_requirements
```

--------------

Для доступа к проекту необходимо создать локальный хост (корневая папка должна указывать на web/).

 
Но проверить работоспособность сайта можно используя возможности Symfony, 
он будет доступен по адресу http://127.0.0.1:8000.

Для запуска выполните команду:

```
php bin/console server:run
```

--------------

Для доступа к UI необходимо зарегистрироваться: http://127.0.0.1:8000/register

А затем войти в систему: http://127.0.0.1:8000/login

--------------

Проект в развернутом виде, можно посмотреть по ссылке: http://fbms.belousovr.com/

Login: roman, Password: 123

API
--------------
Документация к API доступна по ссылке: http://fbms.belousovr.com/doc/api/index.html

В формате Swagger: http://fbms.belousovr.com/doc/api/swagger.json

Сервис для тестирования API: http://petstore.swagger.io

> Подробнее о Swagger: http://swagger.io

[техническим заданием]: tz.md
