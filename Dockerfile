# Dockerfile - VulnBank

FROM debian:stretch

LABEL maintainer="Mikhail Golovanov <migolovanov@ptsecurity.com>"

COPY /sources/www /var/www
COPY /sources/evil /var/evil
COPY /nginx.config /etc/nginx/sites-enabled/default
COPY /start.sh /

RUN DEBIAN_FRONTEND=noninteractive apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
        software-properties-common \
        gnupg2 \
        dirmngr \
        nginx \
        curl \
        php-fpm \
        php-mysql \
        php-curl \
    && mkdir /run/php \
    && chown www-data:www-data -R /var/www \
    && chown www-data:www-data -R /var/evil \
    && apt-key adv --recv-keys --keyserver keyserver.ubuntu.com 0xF1656F24C74CD1D8 \
    && add-apt-repository 'deb [arch=amd64,i386,ppc64el] http://nyc2.mirrors.digitalocean.com/mariadb/repo/10.2/debian stretch main' \
    && DEBIAN_FRONTEND=noninteractive apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y mariadb-server \
    && rm -fr /var/cache/apt/archives/* \
    && chmod +x /start.sh

CMD ["/start.sh"]
