# Task-Manager
> __Task Manager__ is a task management system similar to http://www.redmine.org/. It allows you to set tasks, assign performers and change their statuses. Registration and authentication are required to work with the system.

### Hexlet tests and linter status:
[![Actions Status](https://github.com/tolyod/php-project-lvl4/workflows/hexlet-check/badge.svg)](https://github.com/tolyod/php-project-lvl4/actions/workflows/hexlet-check.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/7f5fc7479c0be183961f/maintainability)](https://codeclimate.com/github/tolyod/php-project-lvl4/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/7f5fc7479c0be183961f/test_coverage)](https://codeclimate.com/github/tolyod/php-project-lvl4/test_coverage)
[![Master workflow](https://github.com/tolyod/php-project-lvl4/workflows/Master%20workflow/badge.svg)](https://github.com/tolyod/php-project-lvl4/actions/workflows/master.yml)

demo of [task-manager](https://task-manager-apoloz.herokuapp.com/) on Heroku

## Requirements
Проверить зависимости PHP можно командой `$ composer check-platform-reqs`

* PHP 8+
* Extensions:
    - curl
    - dom
    - exif
    - fileinfo
    - filter
    - json
    - libxml
    - mbstring
    - openssl
    - pcre
    - PDO
    - pgsql
    - Phar
    - SimpleXML
    - sqlite3
    - tokenizer
    - xml
    - xmlwriter
    - zip
* Composer 2+
* Node.js (v13.11+) & NPM (6.13+)
* make 4+
* SQLite for local, PostgreSQL for production
* [heroku cli](https://devcenter.heroku.com/articles/heroku-cli#download-and-install) [Как развернуть приложение на Heroku](https://ru.hexlet.io/blog/posts/kak-razvernut-prilozhenie-laravel-na-heroku)



## Install
clone repo and call

`$ make setup`

## Start at localhost
`$ make serve`
