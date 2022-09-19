FROM ambientum/php:8.0-nginx

WORKDIR /app

COPY --chown=ambientum:ambientum . /app

USER root

RUN sudo apk update && apk add --no-cache supervisor
COPY supervisord.conf /etc/supervisord.conf
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

USER ambientum

RUN sudo chmod -R 777 /app/storage
RUN sudo rm -rf /var/cache/apk*

EXPOSE 80 443




