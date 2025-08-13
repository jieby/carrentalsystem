# Car Rental System - Docker Setup

This document explains how to run the Car Rental System using Docker.

## Prerequisites

- Docker installed on your system
- Docker Compose installed on your system

## Quick Start

1. **Clone or download the project files**

2. **Navigate to the project directory**
   ```bash
   cd carrentalsystem
   ```

3. **Build and start the containers**
   ```bash
   docker-compose up --build
   ```

4. **Access the application**
   - Main application: http://localhost:8080
   - phpMyAdmin: http://localhost:8081
   - MySQL database: localhost:3306

## Services

### Application (Port 8080)
- PHP 8.1 with Apache
- Your car rental system application
- Accessible at http://localhost:8080

### Database (Port 3306)
- MySQL 8.0
- Database name: `carrental`
- Root password: `password`
- User: `carrental_user`
- User password: `carrental_pass`

### phpMyAdmin (Port 8081)
- Web-based MySQL administration tool
- Accessible at http://localhost:8081
- Login with root/password

## Database Setup

1. **Access phpMyAdmin** at http://localhost:8081
2. **Login** with username `root` and password `password`
3. **Create the database** if it doesn't exist:
   - Click "New" in the left sidebar
   - Enter database name: `carrental`
   - Click "Create"

4. **Import your database schema** (if you have a .sql file):
   - Select the `carrental` database
   - Click "Import" tab
   - Choose your SQL file and click "Go"

## Environment Variables

You can customize the configuration by modifying the `docker-compose.yml` file:

```yaml
environment:
  - DB_HOST=db
  - DB_USER=root
  - DB_PASSWORD=your_password
  - DB_NAME=carrental
```

## File Structure

```
carrentalsystem/
├── Dockerfile              # PHP application container
├── docker-compose.yml      # Multi-container orchestration
├── .dockerignore          # Files to exclude from Docker build
├── backend/
│   ├── db.php            # Original database config
│   └── db-docker.php     # Docker-compatible database config
├── assets/                # Images and static files
├── auth/                  # Authentication files
├── admin/                 # Admin panel files
└── pages/                 # User pages
```

## Switching to Docker Database

To use the Docker database configuration, update your PHP files to include:

```php
require '../backend/db-docker.php';
```

Instead of:
```php
require '../backend/db.php';
```

## Stopping the Application

```bash
# Stop and remove containers
docker-compose down

# Stop and remove containers + volumes (database data will be lost)
docker-compose down -v

# Stop and remove containers + images
docker-compose down --rmi all
```

## Troubleshooting

### Port Already in Use
If you get a port conflict error, modify the ports in `docker-compose.yml`:
```yaml
ports:
  - "8082:80"  # Change 8080 to another port
```

### Database Connection Issues
1. Ensure the database container is running: `docker-compose ps`
2. Check database logs: `docker-compose logs db`
3. Verify environment variables in `docker-compose.yml`

### Permission Issues
If you encounter permission issues with uploaded files:
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/assets/images
```

## Development

For development, the application files are mounted as volumes, so changes to your PHP files will be reflected immediately without rebuilding the container.

## Production Considerations

For production deployment:
1. Use environment variables for sensitive data
2. Set up proper SSL certificates
3. Configure proper database passwords
4. Use a reverse proxy (nginx) if needed
5. Set up proper backup strategies for the database
