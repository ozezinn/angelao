# 1. Define a imagem base com PHP e servidor Apache
# Usamos a versão 8.2, que é moderna e estável.
FROM php:8.2-apache

# 2. Instala as extensões PHP necessárias
# Este comando instala o PDO (camada de acesso a dados) e o driver específico do MySQL.
# Esta é a linha mais importante para resolver o erro "driver not found".
RUN docker-php-ext-install pdo pdo_mysql

# 3. Copia o código da sua aplicação para a pasta do servidor web
# O "." significa "copiar tudo da pasta atual do repositório".
# O destino "/var/www/html/" é a pasta padrão onde o Apache procura os arquivos do site.
COPY . /var/www/html/
