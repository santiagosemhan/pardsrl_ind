#!/bin/bash
php bin/console cache:clear --env=prod --no-debug
php bin/console assets:install --relative --env=prod
chmod -R 777 $SF_CACHE_DIR $SF_LOG_DIR
chown -R www-data:www-data web
# executes all paramaters passed to entrypoint
exec $@
