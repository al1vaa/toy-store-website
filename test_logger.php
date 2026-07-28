<?php
// Подключаем логгер
require_once 'error_log.php';

echo'<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Тест логгера</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #5a7247; }
        .success { color: #28a745; }
        .btn { background: #5a7247; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Тест логгера</h1>';

// Тест 1: Обычная запись
writeToLog('Тестовая запись из test_logger.php', 'TEST');
echo '<p class="success">✅ Тестовая запись создана</p>';

// Тест 2: Предупреждение
trigger_error('Тестовое предупреждение', E_USER_WARNING);
echo '<p class="success">✅ Предупреждение отправлено</p>';

// Тест 3: Проверка файла лога
global $log_file;
if (file_exists($log_file)) {
    $size = filesize($log_file);
    echo '<p class="success">✅ Лог-файл существует, размер: ' . $size . ' байт</p>';
    
    if ($size > 0) {
        echo '<p><a href="view_logs.php" class="btn">📋 Посмотреть логи</a></p>';
    }
} else {
    echo '<p style="color:#dc3545;">❌ Лог-файл не создан</p>';
}

echo '</div></body></html>';
?>