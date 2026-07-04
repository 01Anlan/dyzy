#! /usr/bin/env bash

rm -rf composer.lock vendor/
rm -rf temp/
rm -rf temp2/
rm -rf ve-tos-php-sdk-*.phar
version=$(grep 'private $version' src/Config/ConfigParser.php | grep -oE '[0-9.]+')
composer install --no-dev
phar pack -f ve-tos-php-sdk-$version.phar -x '(.idea|.git|test|script|temp|temp2|.phar|main.php|.phpunit.result.cache)' -c none .

