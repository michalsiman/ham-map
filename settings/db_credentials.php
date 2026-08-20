<?php
//
// Credentials for MySQLi connection to the MySQL/MariaDB database.
//
// All values are read from environment variables so no secrets ever need to
// live in this file. When running via docker-compose, these are provided by
// the `environment:` section (sourced from your local .env file — see
// .env.example for the full list of variables and how to obtain the API
// keys used elsewhere in the app).
//
$host      = getenv('DB_HOST') ?: 'db';
$user      = getenv('DB_USER') ?: 'wwff';
$pass      = getenv('DB_PASS') ?: '';
$db        = getenv('DB_NAME') ?: 'wwff_maps';
$port      = getenv('DB_PORT') ?: '3306';
$charset   = getenv('DB_CHARSET') ?: 'utf8mb4';
$collation = getenv('DB_COLLATION') ?: 'utf8mb4_general_ci';
