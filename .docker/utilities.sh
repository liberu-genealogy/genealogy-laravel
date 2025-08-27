#!/bin/bash

# Laravel Octane Docker Utilities

# Aliases for common Octane commands
alias octane-start='php artisan octane:start --server=swoole --host=0.0.0.0 --port=8000'
alias octane-reload='php artisan octane:reload'
alias octane-stop='php artisan octane:stop'
alias octane-status='php artisan octane:status'

# Aliases for Laravel commands
alias artisan='php artisan'
alias tinker='php artisan tinker'
alias migrate='php artisan migrate'
alias seed='php artisan db:seed'
alias fresh='php artisan migrate:fresh --seed'

# Aliases for Composer
alias composer-install='composer install --no-dev --optimize-autoloader'
alias composer-update='composer update --no-dev --optimize-autoloader'

# Aliases for cache management
alias cache-clear='php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear'
alias cache-optimize='php artisan config:cache && php artisan route:cache && php artisan view:cache'

# Utility functions
function octane-watch() {
    php artisan octane:start --server=swoole --host=0.0.0.0 --port=8000 --watch
}

function logs() {
    tail -f storage/logs/laravel.log
}

function octane-logs() {
    tail -f storage/logs/swoole_http.log
}

echo "Laravel Octane utilities loaded!"
echo "Available commands:"
echo "  octane-start    - Start Octane server"
echo "  octane-reload   - Reload Octane workers"
echo "  octane-stop     - Stop Octane server"
echo "  octane-status   - Check Octane status"
echo "  octane-watch    - Start Octane with file watching"
echo "  cache-clear     - Clear all caches"
echo "  cache-optimize  - Optimize caches"
echo "  logs            - Tail Laravel logs"
echo "  octane-logs     - Tail Octane logs"