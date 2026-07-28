<?php
// Полная диагностика
echo "<h1>Диагностика</h1>";

// Проверка прав на запись
$test_file = __DIR__ . '/logs/test.txt';
if (file_put_contents($test_file, 'test')) {
    echo "<p style='color:green;'>✅ Можно писать в папку logs</p>";
    unlink($test_file);
} else {
    echo "<p style='color:red;'>❌ Нельзя писать в папку logs</p>";
}

// Проверка включенных ошибок
echo "<p>error_reporting: " . error_reporting() . "</p>";
echo "<p>display_errors: " . ini_get('display_errors') . "</p>";

// Список всех PHP файлов
echo "<h2>PHP файлы в папке:</h2>";
$files = glob("*.php");
foreach ($files as $file) {
    $size = filesize($file);
    echo "<p>$file (" . round($size/1024,2) . " KB) - " . substr(sprintf('%o', fileperms($file)), -4) . "</p>";
}