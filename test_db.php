<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Проверка соединения с БД</h2>";

try {
    $db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Соединение с БД успешно установлено!<br>";
    
    // Проверяем существование таблиц
    $tables = ['Products', 'Categories', 'Galleries', 'ProductSpecifications', 'Manufacturers', 'Carts', 'CartItems', 'UserCredentials', 'Reviews'];
    
    foreach ($tables as $table) {
        $stmt = $db->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Таблица '$table' существует<br>";
        } else {
            echo "❌ Таблица '$table' НЕ существует<br>";
        }
    }
    
    // Проверяем структуру таблицы UserCredentials
    echo "<h3>Структура таблицы UserCredentials:</h3>";
    $stmt = $db->query("DESCRIBE UserCredentials");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    // Проверяем есть ли пользователи
    $stmt = $db->query("SELECT COUNT(*) as count FROM UserCredentials");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Количество пользователей: " . $count['count'] . "<br>";
    
} catch (PDOException $e) {
    echo "❌ Ошибка соединения: " . $e->getMessage() . "<br>";
    echo "Код ошибки: " . $e->getCode() . "<br>";
}
?>