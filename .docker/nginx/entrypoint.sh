#!/bin/sh

# Substitui variáveis no autoflow.conf.template
envsubst '${SERVER_NAME} ${ROOT_PATH}' < /etc/nginx/conf.d/autoflow.conf.template > /etc/nginx/conf.d/autoflow.conf
nginx -g "daemon off;"
