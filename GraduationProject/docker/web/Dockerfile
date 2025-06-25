FROM php:apache AS development
# # Set working directory
WORKDIR /var/www/html
#run
RUN docker-php-ext-install mysqli
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini 

# Copy application files
COPY ../../ /var/www/html/


FROM php:apache AS production
# run
RUN docker-php-ext-install mysqli
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini 
# Set working directory
WORKDIR /var/www/html
# Copy application files
COPY ../../ /var/www/html/


