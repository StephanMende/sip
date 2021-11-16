#!/bin/bash

echo ">>> Update Drupal core"
vendor/bin/drush state:set system.maintenance_mode 1
composer update drupal/core "drupal/core-*" --with-all-dependencies

echo ">>> Update Drupal modules"
composer update drupal/* --with-dependencies

echo ">>> Update database"
vendor/bin/drush -y updatedb
vendor/bin/drush state:set system.maintenance_mode 0
vendor/bin/drush cache:rebuild

echo ">>> Export configuration changes"
vendor/bin/drush -y config:export

echo ">>> Done. Now add, commit and push your changes to the GitHub repository."
