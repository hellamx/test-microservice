# Берем легкую сборку
FROM nginx:alpine

# Устанавливаем bash для удобства и apache2-utils для basic auth
RUN apk add --no-cache bash apache2-utils
RUN mkdir -p /etc/nginx/conf
COPY docker/nginx/conf.d /etc/nginx/conf.d
COPY docker/nginx/init.sh /init.sh

RUN chmod +x /init.sh

ENTRYPOINT ["/init.sh"]
