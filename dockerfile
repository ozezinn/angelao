# Use uma imagem oficial do Docker que já vem com Apache e PHP
FROM php:8.2-apache

# Copia todos os arquivos do seu projeto para a pasta do servidor web no container
# O ponto final "." significa "copiar tudo desta pasta"
# O destino /var/www/html/ é a pasta padrão do servidor Apache
COPY . /var/www/html/

# (Opcional) Se você usa o Composer para gerenciar dependências do PHP
# RUN composer install --no-dev --optimize-autoloader
