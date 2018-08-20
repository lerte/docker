#!/bin/bash
docker run --name wordpress --link mysql:mysql -p 80:80 -v ${PWD}/wordpress:/var/www/html:rw -d wordpress