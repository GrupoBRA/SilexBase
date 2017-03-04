#!/bin/sh
# 
# deploy.sh
#
# author: jfranciscos4 <silvaivctd@gmail.com>
# date: 2016/10/27
#
# Usage message
usage()
{
  # echo "Usage: $0 PATH -d DIRPERMS -f FILEPERMS"
  # echo "Arguments:"
  # echo "PATH: path to the root directory you wish to modify permissions for"
  # echo "Options:"
  # echo " -d DIRPERMS, directory permissions"
  # echo " -f FILEPERMS, file permissions"
  exit 1
}

echo "Removendo Artefatos de Desenvovimento"
rm -rf features/ spec/ tests/ build/ apidoc.json build.xml phpspec.yml behat.yml README.md  cache.properties phpunit.xml phpmd.xml phpdox.xml

echo "Removendo vendor"
rm -rf vendor/ bin/ composer.lock CNAME error_log nbproject

echo "Instalando o composer"
php -d allow_url_fopen=on /usr/local/bin/composer install --no-dev --optimize-autoloader --ignore-platform-reqs

echo "Removendo bin"
rm -rf bin/ composer.json composer.lock

echo "Alterando as permissões"
sh deploy/chmodr.sh . 

echo "Removendo Deploy"
rm -rf deploy/  