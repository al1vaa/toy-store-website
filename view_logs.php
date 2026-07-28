<?php
// Максимально простой просмотрщик логов
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');

$log_dir = __DIR__ . '/logs';
$log_file = $log_dir . '/errors_' . date('Y-m-d') . '.log';

// Очистка лога
if (isset($_GET['clear']) && file_exists($log_file)) {
    unlink($log_file);
    header('Location: view_logs.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Просмотр логов</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; }
        .toolbar { margin: 20px 0; }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin-right: 10px;
            background: #5a7247;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover { background: #4a613d; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .info {
            background: #e9ecef;
            padding: 10px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .log-content {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 5px;
            font-family: monospace;
            white-space: pre-wrap;
            max-height: 600px;
            overflow-y: auto;
        }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Логи ошибок</h1>
        
        <div class="toolbar">
            <a href="view_logs.php" class="btn">🔄 Обновить</a>
            <a href="?clear=1" class="btn btn-danger" onclick="return confirm('Очистить лог?')">🗑️ Очистить</a>
            <a href="index.php" class="btn">🏠 На главную</a>
        </div>
        
        <div class="info">
            <strong>Файл:</strong> <?= basename($log_file) ?><br>
            <strong>Существует:</strong> <?= file_exists($log_file) ? '✅ Да' : '❌ Нет' ?><br>
            <?php if (file_exists($log_file)): ?>
                <strong>Размер:</strong> <?= round(filesize($log_file) / 1024, 2) ?> KB<br>
                <strong>Строк:</strong> <?= count(file($log_file)) ?><br>
                <strong>Изменён:</strong> <?= date('d.m.Y H:i:s', filemtime($log_file)) ?>
            <?php endif; ?>
        </div>
        
        <div class="log-content">
            <?php
            if (file_exists($log_file)) {
                $lines = file($log_file);
                if (empty($lines)) {
                    echo "<span style='color:#888;'>Лог пуст</span>";
                } else {
                    // Показываем последние 100 строк в обратном порядке
                    $lines = array_reverse(array_slice($lines, -100));
                    foreach ($lines as $line) {
                        $class = '';
                        if (strpos($line, 'FATAL') !== false || strpos($line, 'ERROR') !== false) {
                            $class = 'error';
                        } elseif (strpos($line, 'WARNING') !== false) {
                            $class = 'warning';
                        }
                        echo "<div class='$class'>" . htmlspecialchars($line) . "</div>";
                    }
                }
            } else {
                echo "<span style='color:#888;'>Лог-файл еще не создан. Посетите любую страницу сайта.</span>";
            }
            ?>
        </div>
    </div>
</body>
</html>