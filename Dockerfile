FROM lorisleiva/laravel-docker

# make the 'app' folder the current working directory
WORKDIR /var/www

# copy both 'package.json' and 'package-lock.json' (if available)
COPY package*.json ./

# copy both 'composer.json' and 'composer.lock' (if available)
COPY composer.json ./
COPY composer.lock ./

#Change php ini files
RUN touch /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 20M;" >> /usr/local/etc/php/conf.d/uploads.ini

# install project dependencies
RUN npm install

# copy project files and folders to the current working directory (i.e. 'app' folder)
COPY . .
RUN composer install

#Pass in api value
ARG MIX_BACKEND_URL
ENV MIX_BACKEND_URL $MIX_BACKEND_URL

# build app for production with minification
RUN npm run prod

CMD php artisan serve --host=0.0.0.0 --port=8080
EXPOSE 8080