<?php
/**
 * BD Hotel - Database connection (PDO + prepared statements)
 * Adjust credentials to match your XAMPP MySQL setup.
 */
declare(strict_types=1);

const DB_HOST = 'localhost';
const DB_NAME = 'bd_hotel';
const DB_USER = 'root';
const DB_PASS = '';       // default XAMPP password is empty
const DB_CHARSET = 'utf8mb4';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $dsn  = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $opts = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
    } catch (PDOException $e) {
        http_response_code(500);
        exit('Database connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES));
    }
    return $pdo;
}
