#!/bin/bash

cd /home/pts2932/repositories/trendline-coffe || exit

git pull origin main

/opt/alt/php83/usr/bin/php -d extension=mbstring.so -d extension=dom.so -d extension=pdo.so -d extension=pdo_mysql.so artisan config:clear
/opt/alt/php83/usr/bin/php -d extension=mbstring.so -d extension=dom.so -d extension=pdo.so -d extension=pdo_mysql.so artisan route:clear
/opt/alt/php83/usr/bin/php -d extension=mbstring.so -d extension=dom.so -d extension=pdo.so -d extension=pdo_mysql.so artisan view:clear