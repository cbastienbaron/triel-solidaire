git pull origin master
composer install --no-dev --optimize-autoloader
rm -fR var/cache/prod
