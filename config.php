<?php
// config.php - Центральный файл конфигурации

// Включаем отображение всех ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1); // Для разработки (на продакшене выключить)
ini_set('display_startup_errors', 1);

// Настройки логирования
define('LOG_PATH', __DIR__ . '/logs/');
define('LOG_FILE', LOG_PATH . 'error_' . date('Y-m-d') . '.log');

// Создаем папку для логов, если её нет
if (!file_exists(LOG_PATH)) {
    mkdir(LOG_PATH, 0755, true);
}

// Устанавлием обработчик ошибок
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $error_message = date('[Y-m-d H:i:s] ') . "Ошибка: [$errno] $errstr в файле $errfile на строке $errline\n";
    error_log($error_message, 3, LOG_FILE);
    
    // Для разработки - показываем ошибки
    if (ini_get('display_errors')) {
        echo "<div style='background:#f8d7da; color:#721c24; padding:10px; margin:10px; border-radius:5px; border:1px solid #f5c6cb;'>";
        echo "<strong>Ошибка:</strong> $errstr<br>";
        echo "<small>Файл: $errfile, строка: $errline</small>";
        echo "</div>";
    }
    
    return true; // Не выполняем стандартный обработчик
}

// Устанавлием обработчик фатальных ошибок
function fatalErrorHandler() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $error_message = date('[Y-m-d H:i:s] ') . "ФАТАЛЬНАЯ ОШИБКА: [{$error['type']}] {$error['message']} в файле {$error['file']} на строке {$error['line']}\n";
        error_log($error_message, 3, LOG_FILE);
        
        if (ini_get('display_errors')) {
            echo "<div style='background:#f8d7da; color:#721c24; padding:20px; margin:20px; border-radius:5px; border:2px solid #f5c6cb;'>";
            echo "<h3>Фатальная ошибка!</h3>";
            echo "<p>{$error['message']}</p>";
            echo "<p>Файл: {$error['file']}:{$error['line']}</p>";
            echo "</div>";
        }
    }
}

// Устанавлием обработчик исключений
function exceptionHandler($exception) {
    $error_message = date('[Y-m-d H:i:s] ') . "ИСКЛЮЧЕНИЕ: " . $exception->getMessage() . 
                     " в файле " . $exception->getFile() . " на строке " . $exception->getLine() . 
                     "\nСтек: " . $exception->getTraceAsString() . "\n";
    error_log($error_message, 3, LOG_FILE);
    
    if (ini_get('display_errors')) {
        echo "<div style='background:#f8d7da; color:#721c24; padding:20px; margin:20px; border-radius:5px; border:2px solid #f5c6cb;'>";
        echo "<h3>Исключение!</h3>";
        echo "<p>{$exception->getMessage()}</p>";
        echo "<p>Файл: {$exception->getFile()}:{$exception->getLine()}</p>";
        echo "<details><summary>Стек вызовов</summary><pre>{$exception->getTraceAsString()}</pre></details>";
        echo "</div>";
    }
}

// Регистрируем обработчики
set_error_handler("customErrorHandler");
set_exception_handler("exceptionHandler");
register_shutdown_function("fatalErrorHandler");

// Функция для логирования SQL запросов (опционально)
function logQuery($query, $params = [], $error = null) {
    static $query_log = [];
    
    $log_entry = [
        'time' => date('Y-m-d H:i:s'),
        'query' => $query,
        'params' => $params,
        'error' => $error
    ];
    
    $query_log[] = $log_entry;
    
    // Записываем в файл если много запросов
    if (count($query_log) > 100) {
        file_put_contents(LOG_PATH . 'queries_' . date('Y-m-d') . '.log', 
                         print_r($query_log, true), FILE_APPEND);
        $query_log = [];
    }
    
    return $log_entry;
}

// Функция для просмотра логов (доступна только админам)
function viewLogs($lines = 50) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 777) {
        die("Доступ запрещен");
    }
    
    echo "<h2>Последние ошибки:</h2>";
    echo "<pre>";
    
    $log_file = LOG_FILE;
    if (file_exists($log_file)) {
        $logs = file($log_file);
        $logs = array_slice($logs, -$lines);
        echo implode('', $logs);
    } else {
        echo "Лог-файл не найден";
    }
    
    echo "</pre>";
}
?>