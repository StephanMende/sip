#!/bin/bash

echo ">>> Backup database"
vendor/bin/drush cache:rebuild
tes=$(date +"%Y%m%dT%H%M").sql
vendor/bin/drush sql:dump --extra-dump=--no-tablespaces > ~/sip_sql_backups/$tes
echo ">>> Database backup created: ~/sip_sql_backups/$tes"

echo ">>> Update Drupal core"
vendor/bin/drush state:set system.maintenance_mode 1
vendor/bin/composer update drupal/core "drupal/core-*" --with-all-dependencies --no-interaction

echo ">>> Update Drupal modules"
vendor/bin/composer update drupal/* --with-dependencies --no-interaction

echo ">>> Update database"
vendor/bin/drush -y updatedb
vendor/bin/drush state:set system.maintenance_mode 0
vendor/bin/drush cache:rebuild

echo ">>> Export configuration changes"
vendor/bin/drush -y config:export

echo ">>> Done. Now add, commit and push your changes to the GitHub repository."
git add .
git commit -m "Update Drupal core/modules"
git push
