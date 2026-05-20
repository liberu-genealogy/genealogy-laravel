# Docker Setup for Laravel Genealogy Application

This document provides comprehensive instructions for running the Laravel Genealogy application using Docker with Laravel Octane for high performance.

## Prerequisites

- Docker Engine 20.10+
- Docker Compose 2.0+
- At least 4GB RAM available for Docker

## Quick Start

1. **Clone and setup environment:**
   ```bash
   git clone <repository-url>
   cd genealogy-laravel
   cp .env.example .env
   ```

2. **Configure environment variables:**
   Edit `.env` file with your database and application settings:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=liberu
   DB_USERNAME=liberu
   DB_PASSWORD=secret
   
   REDIS_HOST=redis
   REDIS_PORT=6379
   
   MAIL_HOST=mailpit
   MAIL_PORT=1025
   ```

3. **Build and start services:**
   ```bash
   docker-compose up -d --build
   ```

4. **Initialize application:**
   ```bash
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate --seed
   docker-compose exec app php artisan storage:link
   ```

5. **Access the application:**
   - Main application: http://localhost:8000
   - Mailpit (email testing): http://localhost:8025

## Services Overview

### Main Application (app)
- **Port:** 8000
- **Server:** Laravel Octane with Swoole
- **Features:** High-performance HTTP server with persistent memory
- **Health Check:** Automatic health monitoring

### Database (mysql)
- **Port:** 3306
- **Version:** MySQL 8.0
- **Credentials:** liberu/secret
- **Persistent Storage:** Docker volume

### Cache (redis)
- **Port:** 6379
- **Version:** Redis 7 Alpine
- **Persistent Storage:** Docker volume

### Mail Testing (mailpit)
- **SMTP Port:** 1025
- **Web Interface:** 8025
- **Purpose:** Email testing and debugging

### Background Services

#### Horizon (horizon)
- **Purpose:** Queue monitoring and processing
- **Dashboard:** Access via main app at `/horizon`

#### Scheduler (scheduler)
- **Purpose:** Laravel task scheduling
- **Runs:** Cron jobs and scheduled tasks

## Laravel Octane Configuration

### Server Options
The application supports multiple Octane servers:

- **Swoole** (default): High-performance, coroutine-based
- **RoadRunner**: Go-based application server
- **FrankenPHP**: Modern PHP application server

### Performance Settings

```env
OCTANE_SERVER=swoole
OCTANE_WORKERS=auto          # Auto-detect CPU cores
OCTANE_TASK_WORKERS=auto     # Background task workers
OCTANE_MAX_EXECUTION_TIME=30 # Request timeout
OCTANE_MAX_REQUEST_SIZE=10485760 # 10MB max request
```

### Memory Management
- **Garbage Collection:** Automatic at 50MB threshold
- **Memory Limit:** 512MB per worker
- **Auto Reload:** Disabled in production

## Container Modes

The application supports different container modes:

### HTTP Mode (default)
```bash
docker-compose up app
```

### Horizon Mode (Queue Processing)
```bash
docker-compose up horizon
```

### Scheduler Mode (Cron Jobs)
```bash
docker-compose up scheduler
```

### Worker Mode (Queue Workers)
```bash
docker-compose up worker
```

## Development Workflow

### File Watching
For development with auto-reload:
```bash
docker-compose exec app octane-watch
```

### Accessing Container
```bash
docker-compose exec app bash
```

### Available Utilities
Inside the container, you have access to helpful aliases:
- `octane-start` - Start Octane server
- `octane-reload` - Reload workers
- `octane-status` - Check server status
- `cache-clear` - Clear all caches
- `cache-optimize` - Optimize caches
- `logs` - Tail Laravel logs
- `octane-logs` - Tail Octane logs

## Performance Optimization

### PHP Configuration
- **OPcache:** Enabled with JIT compilation
- **Memory:** 512MB limit per worker
- **Compression:** Gzip enabled
- **Swoole:** Optimized for genealogy workloads

### Database Optimization
- **Connection Pooling:** Managed by Octane
- **Query Caching:** Redis-backed
- **Indexes:** Optimized for genealogy queries

### Caching Strategy
- **Application Cache:** Redis
- **Session Storage:** Redis
- **Queue Backend:** Redis
- **Octane Cache:** In-memory Swoole table

## Monitoring and Debugging

### Health Checks
- **Octane Status:** `docker-compose exec app php artisan octane:status`
- **Container Health:** `docker-compose ps`
- **Service Logs:** `docker-compose logs -f [service]`

### Performance Monitoring
- **Horizon Dashboard:** Monitor queues and failed jobs
- **Laravel Telescope:** Request/query debugging (if installed)
- **Octane Metrics:** Built-in performance metrics

### Log Files
- **Application:** `storage/logs/laravel.log`
- **Octane:** `storage/logs/swoole_http.log`
- **Horizon:** `storage/logs/horizon.log`
- **Scheduler:** `storage/logs/scheduler.log`

## Production Deployment

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
OCTANE_AUTO_RELOAD=false
WITH_HORIZON=true
WITH_SCHEDULER=true
```

### Security Considerations
- Change default passwords
- Use proper SSL certificates
- Configure firewall rules
- Enable log rotation
- Set up monitoring alerts

### Scaling
- **Horizontal:** Multiple app containers behind load balancer
- **Vertical:** Increase worker count and memory limits
- **Database:** Read replicas and connection pooling
- **Cache:** Redis cluster for high availability

## Troubleshooting

### Common Issues

1. **Port Conflicts:**
   ```bash
   # Check port usage
   netstat -tulpn | grep :8000
   # Change ports in docker-compose.yml
   ```

2. **Memory Issues:**
   ```bash
   # Increase Docker memory limit
   # Monitor container memory usage
   docker stats
   ```

3. **Database Connection:**
   ```bash
   # Check database connectivity
   docker-compose exec app php artisan tinker
   # Test: DB::connection()->getPdo()
   ```

4. **Octane Not Starting:**
   ```bash
   # Check Octane installation
   docker-compose exec app php artisan octane:install swoole
   # Verify configuration
   docker-compose exec app php artisan config:show octane
   ```

### Performance Issues
- Monitor worker memory usage
- Check for memory leaks in application code
- Optimize database queries
- Review cache hit rates

## Maintenance

### Updates
```bash
# Update application
git pull
docker-compose build --no-cache
docker-compose up -d

# Update dependencies
docker-compose exec app composer update
docker-compose exec app php artisan migrate
```

### Backups
```bash
# Database backup
docker-compose exec mysql mysqldump -u liberu -p liberu > backup.sql

# Application files
tar -czf app-backup.tar.gz storage/ public/storage/
```

### Cleanup
```bash
# Remove unused containers and images
docker system prune -a

# Clear application caches
docker-compose exec app cache-clear
```

## Support

For issues and questions:
1. Check container logs: `docker-compose logs -f`
2. Verify configuration: `docker-compose config`
3. Test connectivity: `docker-compose exec app php artisan tinker`
4. Review Laravel logs: `docker-compose exec app logs`

## Additional Resources

- [Laravel Octane Documentation](https://laravel.com/docs/octane)
- [Swoole Documentation](https://www.swoole.co.uk/)
- [Docker Compose Reference](https://docs.docker.com/compose/)
- [MySQL 8.0 Reference](https://dev.mysql.com/doc/refman/8.0/en/)