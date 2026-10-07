<?php
/**
 * MoodBuddy - central configuration
 * --------------------------------------------------------------
 * All secrets and environment-specific values come from environment
 * variables (set them in Render > Environment, or in a local .env file).
 * Never hardcode API keys or passwords in PHP files.
 */

if (!function_exists('app_env')) {

    // Load a local .env file (only used on XAMPP / local dev; Render uses real env vars)
    $envFile = dirname(__DIR__) . '/.env';
    if (is_readable($envFile)) {
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
            [$k, $v] = array_map('trim', explode('=', $line, 2));
            $v = trim($v, "\"'");
            if (getenv($k) === false) {
                putenv("$k=$v");
                $_ENV[$k] = $v;
            }
        }
    }

    function app_env(string $key, $default = null)
    {
        $val = getenv($key);
        if ($val === false || $val === '') {
            $val = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        }
        return ($val === null || $val === '') ? $default : $val;
    }

    /** Public base URL of the site, e.g. https://moodbuddy.onrender.com (no trailing slash) */
    function app_base_url(): string
    {
        $url = app_env('APP_URL');
        if ($url) return rtrim($url, '/');

        // Auto-detect (works behind Render's proxy too)
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        // Project folder relative to document root (e.g. /mood-buddy on XAMPP, empty on Render)
        $docRoot = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: ''), '/');
        $appDir  = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/');
        $sub = ($docRoot && strpos($appDir, $docRoot) === 0) ? substr($appDir, strlen($docRoot)) : '';

        return ($https ? 'https' : 'http') . '://' . $host . $sub;
    }

    /** True when SMTP credentials are configured */
    function app_mail_enabled(): bool
    {
        return (bool) (app_env('SMTP_USER') && app_env('SMTP_PASS'));
    }

    /**
     * Send an HTML email with PHPMailer using SMTP env vars.
     * Returns true on success, or an error string on failure.
     */
    function app_send_mail(string $toEmail, string $toName, string $subject, string $html)
    {
        if (!app_mail_enabled()) return 'SMTP is not configured';

        require_once dirname(__DIR__) . '/vendor/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = app_env('SMTP_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = app_env('SMTP_USER');
            $mail->Password   = app_env('SMTP_PASS');
            $mail->SMTPSecure = app_env('SMTP_SECURE', 'tls');
            $mail->Port       = (int) app_env('SMTP_PORT', 587);
            $mail->Timeout    = 15;

            $mail->setFrom(app_env('MAIL_FROM', app_env('SMTP_USER')), app_env('MAIL_FROM_NAME', 'MoodBuddy'));
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->send();
            return true;
        } catch (Throwable $e) {
            return $mail->ErrorInfo ?: $e->getMessage();
        }
    }

    /** Shared PDO connection (MySQL). Supports SSL for cloud MySQL like Aiven / TiDB. */
    function app_pdo(): PDO
    {
        static $pdo = null;
        if ($pdo) return $pdo;

        $host = app_env('DB_HOST', 'localhost');
        $port = app_env('DB_PORT', '3306');
        $db   = app_env('DB_NAME', 'moodbuddy');
        $user = app_env('DB_USER', 'root');
        $pass = app_env('DB_PASS', '');

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_TIMEOUT            => 10,
        ];

        // Cloud MySQL providers require TLS
        if (filter_var(app_env('DB_SSL', 'false'), FILTER_VALIDATE_BOOLEAN)) {
            $ca = app_env('DB_SSL_CA', '/etc/ssl/certs/ca-certificates.crt');
            if ($ca && is_readable($ca)) {
                $options[PDO::MYSQL_ATTR_SSL_CA] = $ca;
            }
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    }

    /** True when the logged-in user is an admin */
    function app_is_admin(): bool
    {
        return !empty($_SESSION['logged_in']) && ($_SESSION['role'] ?? '') === 'admin';
    }

    // Production-friendly error display
    if (filter_var(app_env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN)) {
        ini_set('display_errors', '1');
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', '0');
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
    }
}
