<?php
/**
 * db.php — PDO singleton.
 * Returns the shared PDO instance; throws RuntimeException on failure.
 */

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // In production hide details; in development show them.
            if (defined('APP_ENV') && APP_ENV === 'development') {
                throw new RuntimeException('DB connection failed: ' . $e->getMessage());
            }
            throw new RuntimeException('Database connection failed. Please try again later.');
        }
    }

    return $pdo;
}
