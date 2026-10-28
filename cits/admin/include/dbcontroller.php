<?php
// Legacy PDO connection wrapper. It intentionally uses the same database settings
// as the rest of CVTS; credentials are loaded by the shared config bootstrap.
require_once __DIR__ . '/config.php';

$DB_host = DB_SERVER;
$DB_user = DB_USER;
$DB_pass = DB_PASS;
$DB_name = DB_NAME;

try {
    $DB_con = new PDO(
        'mysql:host=' . $DB_host . ';dbname=' . $DB_name . ';charset=utf8mb4',
        $DB_user,
        $DB_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    error_log('[CVTS] PDO connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check the local or server database configuration.');
}
?>
