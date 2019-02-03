#!/bin/bash

cd /tmp/customermanager_ci/
sudo rm -rf /opt/bitnami/apache2/htdocs/*
sudo cp -R ./myapp/ /opt/bitnami/apache2/htdocs/