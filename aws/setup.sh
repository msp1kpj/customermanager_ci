#!/bin/bash

cd /tmp/customermanager_ci/
sudo rm -rf /opt/bitnami/apache2/htdocs/*
sudo cp -R ./myapp/* /opt/bitnami/apache2/htdocs/

echo '' >> /opt/bitnami/scripts/setenv.sh
echo '#### Application Environment Variables ####' >> /opt/bitnami/scripts/setenv.sh
echo 'db_pass=$(cat /home/bitnami/bitnami_application_password)' >> /opt/bitnami/scripts/setenv.sh
echo 'DATABASE_SERVER=localhost' >> /opt/bitnami/scripts/setenv.sh
echo 'export DATABASE_SERVER' >> /opt/bitnami/scripts/setenv.sh
echo 'DATABASE_NAME=hpcustomer' >> /opt/bitnami/scripts/setenv.sh
echo 'export DATABASE_NAME' >> /opt/bitnami/scripts/setenv.sh
echo 'DATABASE_USER=root' >> /opt/bitnami/scripts/setenv.sh
echo 'export DATABASE_USER' >> /opt/bitnami/scripts/setenv.sh
echo 'DATABASE_PASSWORD=$db_pass' >> /opt/bitnami/scripts/setenv.sh
echo 'export DATABASE_PASSWORD' >> /opt/bitnami/scripts/setenv.sh

#Allow Database variable from environment
echo 'env[DATABASE_SERVER] = $DATABASE_SERVER' >> /opt/bitnami/php/etc/environment.conf
echo 'env[DATABASE_NAME] = $DATABASE_NAME' >> /opt/bitnami/php/etc/environment.conf
echo 'env[DATABASE_USER] = $DATABASE_USER' >> /opt/bitnami/php/etc/environment.conf
echo 'env[DATABASE_PASSWORD] = $DATABASE_PASSWORD' >> /opt/bitnami/php/etc/environment.conf


