# 🐳 Docker + PHP 7.4 + MySQL + Nginx + Symfony 5 Boilerplate + Test Application

## Description

This is a complete stack for running Symfony 5 into Docker containers using docker-compose tool (it's taken from `https://github.com/ger86/symfony-docker`). Contains test application. Don't forget to run composer & database migrations after the installation.

It is composed by 3 containers:

- `nginx`, acting as the webserver.
- `php`, the PHP-FPM container with the 7.4 PHPversion.
- `db` which is the MySQL database container with a **MySQL 8.0** image.

## Installation

1. 😀 Clone this rep.

2. Run `docker-compose up -d`

3. The 3 containers are deployed: 

```
Creating symfony-docker_db_1    ... done
Creating symfony-docker_php_1   ... done
Creating symfony-docker_nginx_1 ... done
```

4. Use this value for the DATABASE_URL environment variable of Symfony:

```
DATABASE_URL=mysql://app_user:app_pass@db:3306/app_db?serverVersion=5.7
```

You could change the name, user and password of the database in the `env` file at the root of the project.

