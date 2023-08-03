FROM php:8.0-fpm

RUN docker-php-ext-install pdo pdo_mysql

# Install cron
RUN apt-get update && apt-get install -y cron \
    # Clean up the apt cache
        && rm -rf /var/lib/apt/lists/*


# Add docker custom crontab
ADD ./_docker/scheduler/crontab /etc/cron.d/ownhouse

# Specify crontab file for running
RUN crontab /etc/cron.d/ownhouse

# Set up the working directory
WORKDIR /var/www

CMD [ "cron", "-f"]

