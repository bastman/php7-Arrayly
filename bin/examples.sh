#!/usr/bin/env bash
set -ex
composer install && composer update
./vendor/phpunit/phpunit/phpunit -v --debug

php tests/examples/arrayly/example-001.php
php tests/examples/sequence/example-001.php
php tests/examples/flow/example-001.php