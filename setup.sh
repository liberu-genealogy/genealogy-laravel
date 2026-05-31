#!/bin/bash
# Setup script for the Liberu genealogy project.
#
# This script provides installation options for Standalone, Docker, or Kubernetes deployments.
# It handles composer and npm installations with fallback logic and error checking.

set -e  # Exit on error

# Colors for output
RED='\e[91m'
GREEN='\e[92m'
YELLOW='\e[93m'
BLUE='\e[94m'
RESET='\e[39m'

# PHP binary — prefer php85 when available (required for this project)
PHP_BIN="php"
if command -v php85 >/dev/null 2>&1; then
    PHP_BIN="php85"
elif command -v php8.5 >/dev/null 2>&1; then
    PHP_BIN="php8.5"
fi

# Function to print colored messages
print_message() {
    local color=$1
    local message=$2
    echo -e "${color}${message}${RESET}"
}

print_header() {
    echo ""
    echo "=================================="
    echo "$1"
    echo "=================================="
    echo ""
}

print_error() {
    print_message "$RED" "❌ ERROR: $1"
}

print_success() {
    print_message "$GREEN" "✅ $1"
}

print_info() {
    print_message "$BLUE" "ℹ️  $1"
}

print_warning() {
    print_message "$YELLOW" "⚠️  $1"
}

# Check if a command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Verify PHP version >= 8.5
check_php_version() {
    local version
    version=$("$PHP_BIN" -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;' 2>/dev/null)
    local major minor
    major=$(echo "$version" | cut -d. -f1)
    minor=$(echo "$version" | cut -d. -f2)

    if [ "$major" -lt 8 ] || { [ "$major" -eq 8 ] && [ "$minor" -lt 5 ]; }; then
        print_error "PHP 8.5+ is required. Found: $version"
        print_info "Install PHP 8.5 or set PHP_BIN environment variable to the PHP 8.5 binary."
        exit 1
    fi
    print_success "PHP version: $version (OK)"
}

# Download composer.phar if composer is not available
ensure_composer() {
    if command_exists composer; then
        COMPOSER_CMD="composer"
        print_success "Composer is already installed"
        return 0
    fi

    print_warning "Composer command not found. Attempting to download composer.phar..."

    if ! command_exists curl; then
        print_error "curl is required to download Composer. Please install curl or Composer manually."
        return 1
    fi

    print_info "Downloading Composer installer..."
    "$PHP_BIN" -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    "$PHP_BIN" composer-setup.php --quiet
    "$PHP_BIN" -r "unlink('composer-setup.php');"

    if [ -f "composer.phar" ]; then
        print_success "Composer.phar downloaded successfully"
        COMPOSER_CMD="$PHP_BIN composer.phar"
        return 0
    else
        print_error "Failed to download composer.phar"
        return 1
    fi
}

# Install composer dependencies
install_composer_dependencies() {
    print_header "🎬 COMPOSER INSTALL"

    if [ -d "vendor" ] && [ -f "vendor/autoload.php" ]; then
        print_info "Vendor directory already exists."
        read -p "Do you want to reinstall composer dependencies? (y/n) " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            print_success "Skipping composer install"
            return 0
        fi
    fi

    if ! ensure_composer; then
        print_error "Cannot proceed without Composer"
        return 1
    fi

    print_info "Running: $COMPOSER_CMD install"
    if eval "$COMPOSER_CMD install --no-interaction --prefer-dist"; then
        print_success "Composer dependencies installed successfully"
        return 0
    else
        print_error "Composer install failed"
        return 1
    fi
}

# Install npm dependencies
install_npm_dependencies() {
    print_header "🎬 NPM INSTALL"

    if [ -d "node_modules" ]; then
        print_info "node_modules directory already exists."
        read -p "Do you want to reinstall npm dependencies? (y/n) " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            print_success "Skipping npm install"
            return 0
        fi
    fi

    if ! command_exists npm; then
        print_error "npm is not installed. Please install Node.js and npm."
        print_info "Visit: https://nodejs.org/"
        return 1
    fi

    print_info "Running: npm install"
    if npm install; then
        print_success "NPM dependencies installed successfully"
        return 0
    else
        print_error "NPM install failed"
        return 1
    fi
}

# Build frontend assets
build_frontend_assets() {
    print_header "🎬 NPM BUILD"

    if ! command_exists npm; then
        print_error "npm is not installed. Cannot build assets."
        return 1
    fi

    print_info "Running: npm run build"
    if npm run build; then
        print_success "Frontend assets built successfully"
        return 0
    else
        print_error "NPM build failed"
        return 1
    fi
}

# Standalone installation
install_standalone() {
    print_header "STANDALONE INSTALLATION"
    print_info "Starting standalone installation process..."

    check_php_version

    clear
    echo "=================================="
    echo "===== USER: [$(whoami)]"
    echo "===== [$("$PHP_BIN" -r 'echo phpversion();')]"
    echo "=================================="
    echo ""

    # Setup the .env file
    copy=true
    while true; do
        read -p "🎬 DEV ---> DID YOU WANT TO COPY THE .ENV.EXAMPLE TO .ENV? (y/n) " yn
        case $yn in
            [Yy]* )
                print_success "Copying .env.example to .env"
                cp .env.example .env
                copy=true
                break
                ;;
            [Nn]* )
                print_success "Continuing with your .env configuration"
                copy=false
                break
                ;;
            * ) print_warning "Please answer yes or no." ;;
        esac
    done

    if [ "$copy" = true ]; then
        while true; do
            read -p "🎬 DEV ---> DID YOU SETUP YOUR DATABASE CREDENTIALS IN THE .ENV FILE? (y/n) " cond
            case $cond in
                [Yy]* ) print_success "Perfect, let's continue"; break ;;
                [Nn]* )
                    print_warning "Please setup your .env file and run this script again"
                    exit 0
                    ;;
                * ) print_warning "Please answer yes or no." ;;
            esac
        done
    fi

    if ! install_composer_dependencies; then
        print_error "Installation failed at composer install step"
        exit 1
    fi

    install_npm_dependencies || print_warning "NPM install failed, but continuing..."
    build_frontend_assets    || print_warning "NPM build failed, but continuing..."

    print_header "🎬 PHP ARTISAN KEY:GENERATE"
    "$PHP_BIN" artisan key:generate && print_success "Application key generated" || { print_error "Failed to generate application key"; exit 1; }

    print_header "🎬 PHP ARTISAN MIGRATE:FRESH"
    "$PHP_BIN" artisan migrate:fresh && print_success "Database migrated successfully" || { print_error "Database migration failed"; exit 1; }

    print_header "🎬 PHP ARTISAN DB:SEED"
    "$PHP_BIN" artisan db:seed && print_success "Database seeded successfully" || print_warning "Database seeding failed — continuing."

    print_header "🎬 RUNNING TESTS"
    if [ -f "vendor/bin/phpunit" ]; then
        ./vendor/bin/phpunit --no-coverage || print_warning "Some tests failed. Please review the output."
    else
        print_warning "PHPUnit not found. Skipping tests."
    fi

    print_header "🎬 PHP ARTISAN OPTIMIZE:CLEAR"
    "$PHP_BIN" artisan optimize:clear
    "$PHP_BIN" artisan route:clear

    print_success "=================================="
    print_success "============== DONE =============="
    print_success "=================================="

    echo ""
    print_info "Start options:"
    echo "  1) php artisan serve                  (built-in PHP server)"
    echo "  2) php artisan octane:start            (Octane / RoadRunner)"
    echo "  3) php artisan horizon                 (Horizon queue monitor)"
    echo "  4) php artisan reverb:start            (WebSocket server)"
    echo ""

    while true; do
        read -p "🎬 DEV ---> START WITH BUILT-IN SERVER NOW? (y/n) " cond
        case $cond in
            [Yy]* ) print_success "Starting server..."; "$PHP_BIN" artisan serve; break ;;
            [Nn]* ) print_success "Installation complete."; exit 0 ;;
            * ) print_warning "Please answer yes or no." ;;
        esac
    done
}

# Docker installation
install_docker() {
    print_header "DOCKER INSTALLATION"

    if ! command_exists docker; then
        print_error "Docker is not installed. Please install Docker first."
        print_info "Visit: https://docs.docker.com/get-docker/"
        exit 1
    fi
    print_success "Docker is installed"

    if ! command_exists docker-compose && ! docker compose version >/dev/null 2>&1; then
        print_error "Docker Compose is not installed."
        print_info "Visit: https://docs.docker.com/compose/install/"
        exit 1
    fi
    print_success "Docker Compose is available"

    if [ ! -f ".env" ]; then
        print_info "Copying .env.example to .env"
        cp .env.example .env
        print_warning "Please edit .env to configure your Docker environment"
        read -p "Press Enter to continue after editing .env..."
    fi

    print_info "Building and starting Docker containers..."

    local COMPOSE_FILE="docker-compose.yml"
    [ -f "docker-compose.override.yml" ] && print_info "Using docker-compose.override.yml override"

    if command_exists docker-compose; then
        docker-compose up -d --build
    else
        docker compose up -d --build
    fi

    if [ $? -eq 0 ]; then
        print_success "Docker containers started successfully"
        print_info "Application available at: http://localhost:8000"
        print_info ""
        print_info "Useful commands:"
        print_info "  docker compose exec app php artisan migrate"
        print_info "  docker compose exec app php artisan db:seed"
        print_info "  docker compose exec app php artisan horizon"
        print_info "  docker compose logs -f app"
    else
        print_error "Failed to start Docker containers"
        exit 1
    fi
}

# Kubernetes installation
install_kubernetes() {
    print_header "KUBERNETES INSTALLATION"

    if ! command_exists kubectl; then
        print_error "kubectl is not installed. Please install kubectl first."
        print_info "Visit: https://kubernetes.io/docs/tasks/tools/"
        exit 1
    fi
    print_success "kubectl is installed"

    # Determine config directory
    K8S_DIR=""
    for dir in k8s kubernetes; do
        if [ -d "$dir" ]; then
            K8S_DIR="$dir"
            break
        fi
    done

    if [ -z "$K8S_DIR" ]; then
        print_error "No Kubernetes configuration directory found (k8s/ or kubernetes/)."
        print_info "Generate manifests first: ls k8s/ to verify contents."
        exit 1
    fi

    print_info "Using Kubernetes configurations from: $K8S_DIR/"

    if [ ! -f ".env" ]; then
        print_info "Copying .env.example to .env"
        cp .env.example .env
        print_warning "Please edit .env for your Kubernetes environment"
        read -p "Press Enter to continue..."
    fi

    print_info "Applying Kubernetes configurations..."
    if kubectl apply -f "$K8S_DIR/"; then
        print_success "Kubernetes resources created successfully"
        print_info ""
        print_info "Useful commands:"
        print_info "  kubectl get pods"
        print_info "  kubectl get services"
        print_info "  kubectl logs -l app=genealogy-app"
        print_info "  kubectl exec -it \$(kubectl get pod -l app=genealogy-app -o name | head -1) -- php artisan migrate"
    else
        print_error "Failed to apply Kubernetes configurations"
        exit 1
    fi
}

# Main menu
main() {
    clear
    print_header "LIBERU GENEALOGY - INSTALLER"

    echo "Please select an installation type:"
    echo ""
    echo "  1) Standalone  (local development / production)"
    echo "  2) Docker      (containerised deployment)"
    echo "  3) Kubernetes  (K8s cluster deployment)"
    echo "  4) Exit"
    echo ""

    while true; do
        read -p "Enter your choice (1-4): " choice
        case $choice in
            1) install_standalone; break ;;
            2) install_docker; break ;;
            3) install_kubernetes; break ;;
            4) print_info "Installation cancelled"; exit 0 ;;
            *) print_warning "Invalid choice. Please enter 1, 2, 3, or 4." ;;
        esac
    done
}

main
