<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Перенаправление, если нет ID заказа
if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit;
}

$order_id = (int)$_GET['order_id'];

// Перенаправление неавторизованных пользователей
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=order_success&order_id=" . $order_id);
    exit;
}
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
// Получаем информацию о заказе
$stmt = $db->prepare("
    SELECT o.*, 
           (SELECT COUNT(*) FROM OrderItems WHERE order_id = o.order_id) as items_count
    FROM Orders o
    WHERE o.order_id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Если заказ не найден или не принадлежит пользователю
if (!$order) {
    header("Location: index.php");
    exit;
}

// Получаем товары в заказе
$stmt = $db->prepare("
    SELECT oi.*, p.product_name, p.sku
    FROM OrderItems oi
    JOIN Products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем данные пользователя
$stmt = $db->prepare("SELECT * FROM Users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказ оформлен | BirchBark</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Cormorant+Garamond:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #5a7247;
            --secondary-color: #d4a762;
            --bg-light: #f9f5f0;
            --bg-dark: #2a2a2a;
            --text-light: #333;
            --text-dark: #f0f0f0;
        }
        
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-light);
            color: var(--text-light);
            transition: all 0.3s ease;
        }
        h1, h2, h3 {
            font-family: 'Cormorant Garamond', serif;
        }
        
        body.dark-theme {
            background-color: var(--bg-dark);
            color: var(--text-dark);
        }
        
        /* Кнопка назад */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 14px;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        
        .back-btn:hover {
            background-color: #4a613d;
        }
        
        /* Содержимое страницы успеха */
        .success-container {
            padding: 40px 0;
            text-align: center;
        }
        
        .success-icon {
            font-size: 80px;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .dark-theme .success-icon {
            color: var(--secondary-color);
        }
        
        .success-title {
            font-size: 32px;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .dark-theme .success-title {
            color: var(--secondary-color);
        }
        
        /* Современные плашки и поля, как в checkout.php */
        .order-info {
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin: 30px 0;
            text-align: left;
        }
        .dark-theme .order-info {
            background: #1a1a1a;
            box-shadow: 0 2px 10px rgba(0,0,0,0.18);
        }
        .info-row {
            display: flex;
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
            width: 200px;
            color: var(--primary-color);
        }
        .dark-theme .info-label {
            color: var(--secondary-color);
        }
        .order-items {
            margin-top: 30px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            border-radius: 18px;
            background: #f5f5f5;
            padding: 14px 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .dark-theme .order-item {
            border-bottom-color: #444;
            background: #232323;
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
        }
        .order-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid var(--primary-color);
        }
        .btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 18px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .btn:hover {
            background-color: #4a613d;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            color: white;
            font-size: 20px;
            transition: opacity 0.3s;
        }
        
        .social-links a:hover {
            opacity: 0.8;
        }
        
        .copyright {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    </header>

    <div class="container success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="success-title">Заказ успешно оформлен!</h1>
        <p>Спасибо за ваш заказ. Мы свяжемся с вами в ближайшее время для подтверждения.</p>
        
        <div class="order-info">
            <h2>Информация о заказе</h2>
            
            <div class="info-row">
                <div class="info-label">Номер заказа:</div>
                <div>№<?= $order['order_id'] ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Дата заказа:</div>
                <div><?= date('d.m.Y H:i', strtotime($order['order_date'])) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Способ доставки:</div>
                <div>
                    <?= $order['delivery_method'] === 'pickup' ? 'Самовывоз' : 'Доставка' ?>
                    <?php if ($order['delivery_method'] === 'delivery'): ?>
                        (<?= htmlspecialchars($order['delivery_address']) ?>)
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Способ оплаты:</div>
                <div><?= $order['payment_method'] === 'cash' ? 'Наличными' : 'Банковской картой' ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Статус заказа:</div>
                <div>
                    <?php 
                        $statuses = [
                            'pending' => 'Ожидает обработки',
                            'processing' => 'В обработке',
                            'shipped' => 'Отправлен',
                            'delivered' => 'Доставлен',
                            'cancelled' => 'Отменен'
                        ];
                        echo $statuses[$order['status']] ?? $order['status'];
                    ?>
                </div>
            </div>
            
            <?php if (!empty($order['comment'])): ?>
                <div class="info-row">
                    <div class="info-label">Ваш комментарий:</div>
                    <div><?= htmlspecialchars($order['comment']) ?></div>
                </div>
            <?php endif; ?>
            
            <div class="order-items">
                <h3>Состав заказа (<?= $order['items_count'] ?>):</h3>
                
                <?php foreach ($orderItems as $item): ?>
                    <div class="order-item">
                        <div>
                            <div><?= htmlspecialchars($item['product_name']) ?></div>
                            <div style="font-size: 14px; color: #666;">Артикул: <?= $item['sku'] ?></div>
                        </div>
                        <div>
                            <?= $item['quantity'] ?> × <?= number_format($item['price_per_unit'], 2, '.', ' ') ?> ₽
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="order-total">
                    <div>Итого:</div>
                    <div><?= number_format($order['total_amount'], 2, '.', ' ') ?> ₽</div>
                </div>
            </div>
        </div>
        
        <a href="index.php" class="btn">Вернуться в магазин</a>
        <a href="orders.php?tab=orders" class="btn" style="margin-left: 15px;">Мои заказы</a>
    </div>
    <!-- Футер -->
    <footer class="footer">
<?php include 'footer.php'; ?>
    </footer>
</body>
</html>