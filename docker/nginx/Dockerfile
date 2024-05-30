FROM ubuntu

RUN apt-get update \
    && apt-get install -y nginx openssl \
    && openssl req \
      -x509 \
      -subj "/C=US/ST=TX/L=Austin/O=Home/CN=tlt.loc" \
      -nodes \
      -days 365 \
      -newkey rsa:2048
#RUN update-rc.d cron enable

ADD start.sh /usr/local/bin/
RUN chmod a+x /usr/local/bin/start.sh
ADD nginx.conf /etc/nginx/
ADD default.conf /etc/nginx/sites-enabled/
ADD laravel.conf /etc/nginx/sites-enabled/
ADD reverse.conf /etc/nginx/conf.d/


CMD ["/usr/local/bin/start.sh"]

EXPOSE 80
EXPOSE 443
