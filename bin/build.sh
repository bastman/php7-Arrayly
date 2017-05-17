#!/usr/bin/env bash
set -ex
composer install && composer update
./vendor/phpunit/phpunit/phpunit -v --debug