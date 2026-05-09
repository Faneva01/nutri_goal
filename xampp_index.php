<?php

// Configuration PHP pour XAMPP
ini_set('mysql.default_socket', '/opt/lampp/var/mysql/mysql.sock');
ini_set('mysqli.default_socket', '/opt/lampp/var/mysql/mysql.sock');
ini_set('pdo_mysql.default_socket', '/opt/lampp/var/mysql/mysql.sock');

// Démarrer CodeIgniter
require_once __DIR__ . '/index.php';