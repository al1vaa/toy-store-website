<?php
/**
 * error_log.php - МАКСИМАЛЬНО ПРОСТАЯ РАБОЧАЯ ВЕРСИЯ
 * Совместима с любой версией PHP
 */

// Включаем показ всех ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: text/html; charset=utf-8');

// Создаём папку для логов
$log_dir = dirname(__FILE__) . '/logs';
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0777, true);
}

$log_file = $log_dir . '/errors_' . date('Y-m-d') . '.log';

// Простая функция записи в лог (БЕЗ ИСПОЛЬЗОВАНИЯ ??)
function writeToLog($message, $type = 'INFO') {
    global $log_file;
    
    // Определяем имя файла безопасным способом
    $script_name = 'unknown';
    if (isset($_SERVER['SCRIPT_NAME'])) {
        $script_name = basename($_SERVER['SCRIPT_NAME']);
    } elseif (isset($_SERVER['PHP_SELF'])) {
        $script_name = basename($_SERVER['PHP_SELF']);
    }
    
    $log_entry = "[" . date('Y-m-d H:i:s') . "] [" . $type . "] [" . $script_name . "] " . $message . "\n";
    
    // Записываем в файл
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

// Простой обработчик ошибок
function errorHandler($errno, $errstr, $errfile, $errline) {
    $types = array(
        E_ERROR => 'FATAL',
        E_WARNING => 'WARNING',
        E_PARSE => 'PARSE',
        E_NOTICE => 'NOTICE',
        E_USER_ERROR => 'USER_ERROR',
        E_USER_WARNING => 'USER_WARNING',
        E_USER_NOTICE => 'USER_NOTICE'
    );
    
    $type = isset($types[$errno]) ? $types[$errno] : 'ERROR';
    writeToLog($type . ': ' . $errstr . ' in ' . basename($errfile) . ':' . $errline, 'PHP_ERROR');
    
    // Показываем ошибку на экране
    echo '<div style="background:#ffebee; color:#c62828; padding:10px; margin:5px; border-left:4px solid #c62828; font-family:monospace;">';
    echo '<strong>' . $type . ':</strong> ' . htmlspecialchars($errstr) . '<br>';
    echo '<small>Файл: ' . basename($errfile) . ' строка: ' . $errline . '</small>';
    echo '</div>';
    
    return true;
}

// Обработчик фатальных ошибок
function shutdownHandler() {
    $error = error_get_last();
    if ($error !== NULL) {
        $fatal_types = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR);
        if (in_array($error['type'], $fatal_types)) {
            writeToLog('FATAL: ' . $error['message'] . ' in ' . basename($error['file']) . ':' . $error['line'], 'FATAL');
            
            echo '<div style="background:#ffcdd2; color:#b71c1c; padding:20px; margin:20px; border:2px solid #b71c1c; border-radius:5px;">';
            echo '<h2>⚠️ Фатальная ошибка!</h2>';
            echo '<p><strong>' . htmlspecialchars($error['message']) . '</strong></p>';
            echo '<p>Файл: ' . basename($error['file']) . ' строка: ' . $error['line'] . '</p>';
            echo '</div>';
        }
    }
}

// Регистрируем обработчики
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");

// Записываем факт запуска
writeToLog('Файл загружен: ' . basename($_SERVER['PHP_SELF']), 'INFO');

// Если файл вызван напрямую
if (basename($_SERVER['PHP_SELF']) == 'error_log.php') {
    echo '<h2 style="color:#5a7247;">✅ error_log.php загружен</h2>';
    echo '<p><strong>Лог-файл:</strong> ' . $log_file . '</p>';
    echo '<p><strong>Папка logs:</strong> ' . $log_dir . '</p>';
    echo '<p><a href="test_logger.php" style="background:#5a7247; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;">🧪 Запустить тест</a> ';
    echo '<a href="view_logs.php" style="background:#5a7247; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;">📋 Просмотр логов</a></p>';
}
?>