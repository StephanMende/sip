#!/bin/bash

echo ">>> Pull changes from GitHub Repository"
git pull

echo ">>> Backup database"
vendor/bin/drush cache:rebuild
tes=$(date +"%Y%m%dT%H%M").sql
vendor/bin/drush sql:dump --extra-dump=--no-tablespaces > ~/$tes
echo ">>> Database backup created: ~/$tes"

echo ">>> Update Composer dependencies"
composer install

echo ">>> Update database"
vendor/bin/drush -y updatedb
vendor/bin/drush -y config:import
vendor/bin/drush cache:rebuild

