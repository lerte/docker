#!/bin/bash
docker run -it --name wad -p 80:80 \
-v ${PWD}/wad/conf/php-fpm.conf:/etc/php/7.0/fpm/pool.d/www.conf \
-v ${PWD}/wad/conf/mysql.cnf:/etc/mysql/conf.d/mysql.cnf \
-v ${PWD}/wad/mysql:/var/lib/mysql \
-v ${PWD}/wad/conf/nginx.conf:/etc/nginx/conf.d/default.conf \
-v ${PWD}/wad/html:/usr/share/nginx/html lerte/wad bash