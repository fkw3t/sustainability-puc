#!/bin/bash
set -e

# Verifica se a pasta vendor existe
if [ ! -d "/opt/www/vendor" ]; then
    composer install
fi

# Inicia o servidor de watch
php /opt/www/bin/hyperf.php server:watch