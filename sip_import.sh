#!/bin/bash

echo ">>> Pull changes from GitHub Repository"
vendor/bin/drush -y config:export
git pull

echo ">>> Backup database"
vendor/bin/drush cache:rebuild
tes=$(date +"%Y%m%dT%H%M").sql
vendor/bin/drush sql:dump --extra-dump=--no-tablespaces > ~/sip_sql_backups/$tes
echo ">>> Database backup created: ~/sip_sql_backups/$tes"

echo ">>> Update Composer dependencies"
vendor/bin/composer install --no-interaction

echo ">>> Update database"
vendor/bin/drush -y updatedb
vendor/bin/drush -y config:import
vendor/bin/drush cache:rebuild

