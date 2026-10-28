<?php
/**
 * CVTS database bootstrap.
 *
 * Local development: copy config.local.example.php to config.local.php and edit it.
 * Production: set CVTS_DB_* environment variables or CVTS_CONFIG_FILE to a private
 * PHP config file stored outside the public web root. Never commit production secrets.
 */
$cvtsEnvironment = strtolower((string) (getenv('CVTS_ENV') ?: 'development'));
$cvtsConfigFile = getenv('CVTS_CONFIG_FILE');
if ($cvtsConfigFile === false || $cvtsConfigFile === '') {
    $cvtsConfigFile = __DIR__ . '/config.local.php';
}
$cvtsLocalConfigLoaded = is_file($cvtsConfigFile);
if ($cvtsLocalConfigLoaded) {
    require_once $cvtsConfigFile;
}

if ($cvtsEnvironment === 'production' && !$cvtsLocalConfigLoaded) {
    foreach (['CVTS_DB_HOST', 'CVTS_DB_USER', 'CVTS_DB_PASS', 'CVTS_DB_NAME'] as $requiredSetting) {
        $settingValue = getenv($requiredSetting);
        if ($settingValue === false || $settingValue === '') {
            error_log('[CVTS] Missing required production database setting: ' . $requiredSetting);
            http_response_code(500);
            exit('The database configuration is incomplete. Ask the site administrator to check the server configuration.');
        }
    }
}

if (!defined('DB_SERVER')) {
    $value = getenv('CVTS_DB_HOST');
    define('DB_SERVER', ($value !== false && $value !== '') ? $value : '127.0.0.1');
}
if (!defined('DB_USER')) {
    $value = getenv('CVTS_DB_USER');
    define('DB_USER', ($value !== false && $value !== '') ? $value : 'root');
}
if (!defined('DB_PASS')) {
    $value = getenv('CVTS_DB_PASS');
    define('DB_PASS', ($value !== false) ? $value : '');
}
if (!defined('DB_NAME')) {
    $value = getenv('CVTS_DB_NAME');
    define('DB_NAME', ($value !== false && $value !== '') ? $value : 'cits1');
}

mysqli_report(MYSQLI_REPORT_OFF);
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$con) {
    error_log('[CVTS] MySQL connection failed: ' . mysqli_connect_error());
    http_response_code(500);
    exit('Database connection failed. Check the local or server database configuration.');
}
mysqli_set_charset($con, 'utf8mb4');
?>
