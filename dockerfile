# Usa uma imagem oficial do Docker que já vem com Apache e PHP
FROM php:8.2-apache

# Instala o driver do MySQL para que o PDO possa se conectar
RUN docker-php-ext-install pdo pdo_mysql

# Copia os arquivos do seu projeto para a pasta do servidor
COPY . /var/www/html/
