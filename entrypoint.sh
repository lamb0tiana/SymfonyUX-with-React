#!/bin/bash
composer install && yarn install && yarn run build
php bin/console d:d:c --if-not-exists && php bin/console d:m:m -n

/usr/sbin/apache2ctl -D FOREGROUND