# Liberu Genealogy

[![PHP](https://img.shields.io/badge/PHP-8.4-informational?style=flat&logo=php&color=4f5b93)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-12-informational?style=flat&logo=laravel&color=ef3b2d)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-4.0-informational?style=flat&color=fdae4b)](https://filamentphp.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.5-informational?style=flat&color=fb70a9)](https://laravel-livewire.com)
[![Jetstream](https://img.shields.io/badge/Jetstream-5-purple.svg)](https://github.com/laravel/jetstream)
[![Socialite](https://img.shields.io/badge/Socialite-latest-brightgreen.svg)](https://github.com/laravel/socialite)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

[![Latest Release](https://img.shields.io/github/release/liberu-genealogy/genealogy-laravel.svg)](https://github.com/liberu-genealogy/genealogy-laravel/releases)
[![Install](https://github.com/liberu-genealogy/genealogy-laravel/actions/workflows/install.yml/badge.svg)](https://github.com/liberu-genealogy/genealogy-laravel/actions/workflows/install.yml)
[![Tests](https://github.com/liberu-genealogy/genealogy-laravel/actions/workflows/tests.yml/badge.svg)](https://github.com/liberu-genealogy/genealogy-laravel/actions/workflows/tests.yml)
[![Docker CI](https://github.com/liberu-genealogy/genealogy-laravel/actions/workflows/main.yml/badge.svg)](https://github.com/liberu-genealogy/genealogy-laravel/actions/workflows/main.yml)
[![Codecov](https://codecov.io/gh/liberu-genealogy/genealogy-laravel/branch/main/graph/badge.svg)](https://codecov.io/gh/liberu-genealogy/genealogy-laravel)

---

A modern genealogy web application built with Laravel and Filament. This repository provides the application source, deployment tooling, and developer resources for running and contributing to Liberu Genealogy.

Key goals:
- Provide a performant, extensible genealogy platform.
- Support GEDCOM import/export and DNA-matching integrations.
- Offer a developer-friendly, modular codebase.

Quick links: Demo • Hosting
- Demo: https://familytree365.com
- Managed hosting: https://liberu.co.uk

## Quick start

Requirements: PHP 8.4, Composer, a database (MySQL / MariaDB / PostgreSQL), and optional Docker.

1. Clone the repository:

   git clone https://github.com/liberu-genealogy/genealogy-laravel.git
   cd genealogy-laravel

2. Install and prepare (automated):

   On Unix/macOS:
   ```bash
   ./setup.sh
   ```

   Or run the manual steps:
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   ```

   Notes:
   - The setup script may offer to overwrite `.env` with `.env.example`. Choose carefully if you already have an `.env` file.
   - Seeders will run during setup; skip them if you do not want sample data.

## Docker

Build and run the included image (simple local test):

```bash
docker build -t genealogy-laravel .
docker run -p 8000:8000 genealogy-laravel
```

For development with containers, Laravel Sail or Docker Compose are recommended.

## Laravel Sail

Start the Sail development environment:

```bash
./vendor/bin/sail up -d
```

Visit http://localhost once containers are ready.

## Related projects

A curated list of related Liberu repositories.

| Project | Repository |
|---|---|
| Accounting | https://github.com/liberu-accounting/accounting-laravel |
| Automation | https://github.com/liberu-automation/automation-laravel |
| Billing | https://github.com/liberu-billing/billing-laravel |
| Boilerplate | https://github.com/liberusoftware/boilerplate |
| Browser game | https://github.com/liberu-browser-game/browser-game-laravel |
| CMS | https://github.com/liberu-cms/cms-laravel |
| Control panel | https://github.com/liberu-control-panel/control-panel-laravel |
| CRM | https://github.com/liberu-crm/crm-laravel |
| E-commerce | https://github.com/liberu-ecommerce/ecommerce-laravel |
| Genealogy (this repo) | https://github.com/liberu-genealogy/genealogy-laravel |
| Maintenance | https://github.com/liberu-maintenance/maintenance-laravel |
| Real estate | https://github.com/liberu-real-estate/real-estate-laravel |
| Social network | https://github.com/liberu-social-network/social-network-laravel |

## Contributing

Please read `CONTRIBUTING.md` and `CODE_OF_CONDUCT.md` before submitting pull requests. The project includes unit and feature tests—run them locally and ensure they pass.

- Run tests:

```bash
vendor/bin/phpunit
```

## License

This project is licensed under the MIT License — see the `LICENSE` file for details.

## Community & Support

- Issues: https://github.com/liberu-genealogy/genealogy-laravel/issues
- Discussions / feature requests are welcome via the repository.

---

Maintainers: Liberu Genealogy team

Contributors: see https://github.com/liberu-genealogy/genealogy-laravel/graphs/contributors
