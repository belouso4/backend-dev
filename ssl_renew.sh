#!/bin/bash

COMPOSE="/usr/local/bin/docker-compose --ansi never -f docker-compose.prod.yaml"
DOCKER="/usr/bin/docker"

cd /var/www/own-house/api/
ls -la
echo "-------------------------------------------------------------------------------------"
$COMPOSE run certbot renew --dry-run && $COMPOSE kill -s SIGHUP api_web
$DOCKER system prune -af

