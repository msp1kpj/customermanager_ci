#!/bin/bash

cd /tmp/customermanager_ci/
sudo rm -rf /opt/bitnami/apache2/htdocs/*
sudo cp -R ./myapp/* /opt/bitnami/apache2/htdocs/
sudo cp ./php/bitnami-apps-vhosts.conf /opt/bitnami/apache2/conf/bitnami/bitnami-apps-vhosts.conf

echo '' > /opt/bitnami/scripts/setenv_app.sh
echo '#### Application Environment Variables ####' >> /opt/bitnami/scripts/setenv_app.sh
echo 'db_pass=$(cat /home/bitnami/bitnami_application_password)' >> /opt/bitnami/scripts/setenv_app.sh
echo 'DATABASE_SERVER=localhost' >> /opt/bitnami/scripts/setenv_app.sh
echo 'export DATABASE_SERVER' >> /opt/bitnami/scripts/setenv_app.sh
echo 'DATABASE_NAME=hpcustomer' >> /opt/bitnami/scripts/setenv_app.sh
echo 'export DATABASE_NAME' >> /opt/bitnami/scripts/setenv_app.sh
echo 'DATABASE_USER=root' >> /opt/bitnami/scripts/setenv_app.sh
echo 'export DATABASE_USER' >> /opt/bitnami/scripts/setenv_app.sh
echo 'DATABASE_PASSWORD=$db_pass' >> /opt/bitnami/scripts/setenv_app.sh
echo 'export DATABASE_PASSWORD' >> /opt/bitnami/scripts/setenv_app.sh
echo 'CI_ENV=production' >> /opt/bitnami/scripts/setenv_app.sh
echo 'export CI_ENV' >> /opt/bitnami/scripts/setenv_app.sh


LINE='. /opt/bitnami/scripts/setenv_app.sh'
FILE=/opt/bitnami/scripts/setenv.sh
grep -qF -- "$LINE" "$FILE" || echo "$LINE" >> "$FILE"


#Allow Database variable from environment
echo '' > /opt/bitnami/php/etc/environment_app.conf
echo 'env[DATABASE_SERVER] = $DATABASE_SERVER' >> /opt/bitnami/php/etc/environment_app.conf
echo 'env[DATABASE_NAME] = $DATABASE_NAME' >> /opt/bitnami/php/etc/environment_app.conf
echo 'env[DATABASE_USER] = $DATABASE_USER' >> /opt/bitnami/php/etc/environment_app.conf
echo 'env[DATABASE_PASSWORD] = $DATABASE_PASSWORD' >> /opt/bitnami/php/etc/environment_app.conf
echo 'env[CI_ENV] = $CI_ENV' >> /opt/bitnami/php/etc/environment_app.conf

LINE='include=/opt/bitnami/php/etc/environment_app.conf'
FILE=/opt/bitnami/php/etc/environment.conf
grep -qF -- "$LINE" "$FILE" || echo "$LINE" >> "$FILE"


