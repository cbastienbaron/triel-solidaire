FROM alpine:edge

RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories
RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/main" >> /etc/apk/repositories
RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories
#RUN apk update && apk upgrade && \
#    echo @edge http://nl.alpinelinux.org/alpine/edge/community >> /etc/apk/repositories && \
#    echo @edge http://nl.alpinelinux.org/alpine/edge/main >> /etc/apk/repositories && \
#    apk add --no-cache \
#      chromium@edge \
#      nss@edge

#RUN docker-php-ext-install intl 

RUN apk add --update \
    openssl \
    openssl-dev \
    g++ \
    git \
    php7-dev \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-json \
    # icu-libs \
    # icu-dev \
    # php7-intl \
    php7-mcrypt \
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xml \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-xmlwriter \
    php7-xsl \
    # icu-libs-dev \
    # libxslt-dev \
    # libgcrypt-dev \
    # php7-xsl \
    gearmand \
    #libressl2.7-libcrypto \
    imagemagick \
    imagemagick6 \
    imagemagick6-dev \
    #php7-imagemagick \
    php7-pecl-imagick \
    php7-imagick \
    php7-gearman \
    php7-fileinfo \
    ffmpeg \
    make \
    curl \
    gcc \
    musl-dev \
    python3 \
    python3-dev \
    py-pip \
    libffi-dev \
    supervisor


# RUN cd /opt && git clone https://github.com/swoole/swoole-src.git && \
#     cd swoole-src && \
#     git checkout v4.4.x && \
#     phpize  && \
#     ./configure --with-openssl-dir --enable-openssl && \
#     make && make install

# RUN touch /etc/php7/conf.d/swoole.ini && \
#     echo 'extension=swoole.so' > /etc/php7/conf.d/swoole.ini

# Tell Puppeteer to skip installing Chrome. We'll be using the installed package.
#ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD true

# Puppeteer v1.4.0 works with Chromium 68.
#RUN yarn add puppeteer@1.4.0

#RUN npm install puppeteer -- run localy DNS docker pbl
#RUN pip3 install proxybroker
RUN pip3 install youtube_dl
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer && /usr/bin/composer self-update

ADD symfony.ini /etc/php7/php-fpm.d/
ADD symfony.ini /etc/php7/cli/conf.d/
#ADD supervisor.notification.ini /etc/supervisor.d/
ADD symfony.pool.conf /etc/php7/php-fpm.d/

COPY start.sh /start.sh

CMD ["/start.sh"]

WORKDIR /app
EXPOSE 9000
