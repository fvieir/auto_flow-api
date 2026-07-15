#!/bin/sh

CERTS_DIR="/etc/nginx/certs"
CERT_KEY="${CERTS_DIR}/nginx-selfsigned.key"
CERT_CRT="${CERTS_DIR}/nginx-selfsigned.crt"

# Verifica se o certificado já existe
if [ ! -f "$CERT_KEY" ] || [ ! -f "$CERT_CRT" ]; then
  echo "Certificados não encontrados. Gerando certificados SSL autoassinados..."

  # Cria o diretório de certificados se não existir
  mkdir -p $CERTS_DIR

  # Gera o certificado autoassinado
  openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout "$CERT_KEY" -out "$CERT_CRT" -subj "/CN=localhost"

  echo "Certificados SSL gerados em $CERTS_DIR"
else
  echo "Certificados já existem. Pulando a geração."
fi

# Executa o Nginx normalmente
nginx -g "daemon off;"
