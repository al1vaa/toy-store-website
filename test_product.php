<?php
// Тест подключения
echo "1. Начало<br>";

require_once 'db.php';
echo "2. db.php подключен<br>";

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
echo "3. ID товара: $product_id<br>";

$stmt = $db->prepare("SELECT * FROM Products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
echo "4. Запрос выполнен<br>";

if ($product) {
    echo "5. Товар найден: " . $product['product_name'] . "<br>";
} else {
    echo "5. Товар не найден<br>";
}

phpinfo();