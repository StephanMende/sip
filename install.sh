#!/bin/bash

#Install SIP dependencies
composer install

#Install Drupal
vendor/bin/drush -y site:install standard \
    --account-name=admin \
    --account-pass=password \
    --db-url=mysql://root:sip@sip_db:3306/sip

#Import SIP database
vendor/bin/drush -y sql:drop
vendor/bin/drush sql:cli < sql_dumps/dump.sql
