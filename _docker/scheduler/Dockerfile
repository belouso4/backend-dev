FROM php:8.0-fpm

RUN docker-php-ext-install pdo pdo_mysql

# Install cron
RUN apt-get update && apt-get install -y cron

# Add docker custom crontab
ADD ./_docker/scheduler/crontab /etc/cron.d/ownhouse

# Specify crontab file for running
RUN crontab /etc/cron.d/ownhouse

CMD [ "cron", "-f"]

