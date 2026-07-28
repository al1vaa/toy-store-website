<?php


// Настройки подключения
$host = 'localhost';
$dbname = 'supercy8_bd';
$username = 'supercy8_bd';
$password = 'al1vaawitch133711072005!'; // Пароль из дампа БД

try {
    // Создаем подключение PDO
    $db = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Режим ошибок - исключения
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Режим выборки по умолчанию
            PDO::ATTR_EMULATE_PREPARES => false, // Использовать настоящие подготовленные запросы
        ]
    );
} catch (PDOException $e) {
    // Логируем ошибку (в реальном проекте используйте логирование)
    error_log("Ошибка подключения к БД: " . $e->getMessage());
    
    // Показываем пользователю общее сообщение
    die("Извините, возникла проблема с подключением к базе данных. Пожалуйста, попробуйте позже.");
}
?>