#!/bin/sh

set -e

gearmand -d
supervisord -c /etc/supervisord.conf
php-fpm7 -F
