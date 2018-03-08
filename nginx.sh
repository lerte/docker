#!/bin/bash
docker run --name nginx -p 80:80 -v ${PWD}/nginx/conf:/etc/nginx/conf.d -v ${PWD}/nginx/html:/usr/share/nginx/html:ro -d nginx