#!/bin/bash
docker run --name mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=123456 -v ${PWD}/mysql/conf:/etc/mysql/conf.d -v ${PWD}/mysql/data:/var/lib/mysql -d mysql