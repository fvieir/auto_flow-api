FROM nginx:1.29.5-alpine

RUN apk add --no-cache bash envsubst

COPY conf/*.template /etc/nginx/conf.d/
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80 443
ENTRYPOINT ["/entrypoint.sh"]
