<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Перенаправление неавторизованных пользователей
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout");
    exit;
}

$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
// Получаем данные пользователя
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM Users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Получаем корзину пользователя
$cartItems = [];
$total = 0;
$stmt = $db->prepare("SELECT * FROM Carts WHERE user_id = ? AND is_active = 1");
$stmt->execute([$user_id]);
$cart = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cart) {
    $stmt = $db->prepare("
        SELECT ci.*, p.product_name, p.price, p.sku
        FROM CartItems ci
        JOIN Products p ON ci.product_id = p.product_id
        WHERE ci.cart_id = ?
    ");
    $stmt->execute([$cart['cart_id']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}

// Обработка оформления заказа
$orderSuccess = false;
$orderData = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $delivery_method = $_POST['delivery_method'];
    $payment_method = $_POST['payment_method'];
    $address = $delivery_method === 'delivery' ? $_POST['address'] : 'Самовывоз';
    $comment = $_POST['comment'] ?? '';
    
    try {
        $db->beginTransaction();
        
        // Создаем заказ
        $stmt = $db->prepare("
            INSERT INTO Orders (user_id, order_date, total_amount, delivery_method, delivery_address, payment_method, status, comment)
            VALUES (?, NOW(), ?, ?, ?, ?, 'pending', ?)
        ");
        $stmt->execute([
            $user_id,
            $total,
            $delivery_method,
            $address,
            $payment_method,
            $comment
        ]);
        $order_id = $db->lastInsertId();
        
        // Добавляем товары в заказ
        foreach ($cartItems as $item) {
            $stmt = $db->prepare("
                INSERT INTO OrderItems (order_id, product_id, quantity, price_per_unit)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $order_id,
                $item['product_id'],
                $item['quantity'],
                $item['price']
            ]);
        }
        
        // Деактивируем корзину
        $stmt = $db->prepare("UPDATE Carts SET is_active = 0 WHERE cart_id = ?");
        $stmt->execute([$cart['cart_id']]);
        
        $db->commit();
        
        // Получаем данные о заказе для отображения
        $stmt = $db->prepare("
            SELECT o.*, 
                   (SELECT COUNT(*) FROM OrderItems WHERE order_id = o.order_id) as items_count
            FROM Orders o
            WHERE o.order_id = ?
        ");
        $stmt->execute([$order_id]);
        $orderData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Получаем товары в заказе
        $stmt = $db->prepare("
            SELECT oi.*, p.product_name, p.sku
            FROM OrderItems oi
            JOIN Products p ON oi.product_id = p.product_id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order_id]);
        $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $orderSuccess = true;
        
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['order_error'] = "Ошибка при оформлении заказа: " . $e->getMessage();
        header("Location: checkout.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа | BirchBark</title>
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
        
        /* Общие стили */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
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
        
        /* Содержимое checkout */
        .checkout-container {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 30px;
            padding: 40px 0;
        }
        
        .checkout-section {
            background: white;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            min-width: 380px;
            max-width: 520px;
        }
        
        .dark-theme .checkout-section {
            background: #1a1a1a;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .section-title {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 22px;
        }
        
        .dark-theme .section-title {
            color: var(--secondary-color);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select,
        textarea {
            width: 80%;
            padding: 22px 28px;
            border: none;
            border-radius: 18px;
            background: #f5f5f5;
            color: var(--text-light);
            font-size: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: box-shadow 0.2s, border 0.2s;
        }
        
        .dark-theme input[type="text"],
        .dark-theme input[type="email"],
        .dark-theme input[type="tel"],
        .dark-theme select,
        .dark-theme textarea {
            background: #232323;
            color: var(--text-dark);
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
            resize: none;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        textarea:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--primary-color), 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .order-summary {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 18px;
        }
        
        .dark-theme .order-summary {
            background: #2a2a2a;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .dark-theme .order-item {
            border-bottom-color: #444;
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
            border-radius: 14px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 15px;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #4a613d;
        }
        
        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
            padding: 10px;
            background-color: rgba(231, 76, 60, 0.1);
            border-radius: 14px;
        }
        
        /* Плашки выбора способа доставки и оплаты */
        .delivery-methods, .payment-methods {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        
        .delivery-methods input[type="radio"], .payment-methods input[type="radio"] {
            display: none;
        }
        
        .delivery-btn, .payment-btn {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            background: #f5f5f5;
            border: 2px solid transparent;
            border-radius: 24px;
            padding: 18px 24px;
            cursor: pointer;
            min-width: 120px;
            max-width: 180px;
            transition: border 0.2s, background 0.2s;
            font-size: 16px;
            position: relative;
        }
        
        .payment-btn {
            flex-direction: row;
            align-items: center;
            gap: 16px;
        }
        
        .delivery-btn.selected, .payment-btn.selected {
            border: 2px solid var(--primary-color);
            background: #eaf3e2;
        }
        
        .dark-theme .delivery-btn, .dark-theme .payment-btn {
            background: #232323;
            color: var(--text-dark);
        }
        
        .dark-theme .delivery-btn.selected, .dark-theme .payment-btn.selected {
            background: #2a2a2a;
            border-color: var(--secondary-color);
        }
        
        .delivery-title, .payment-title {
            font-weight: bold;
            font-size: 17px;
        }
        
        .delivery-desc, .payment-desc {
            font-size: 14px;
            color: #888;
        }
        
        .dark-theme .delivery-desc, .dark-theme .payment-desc {
            color: #bbb;
        }
        
        .payment-btn i {
            font-size: 28px;
            color: var(--primary-color);
        }
        
        .dark-theme .payment-btn i {
            color: var(--secondary-color);
        }
        
        /* Модальное окно успешного заказа */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-content {
            background-color: var(--bg-light);
            border-radius: 18px;
            padding: 30px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transform: translateY(-20px);
            transition: transform 0.3s;
        }
        
        .dark-theme .modal-content {
            background-color: var(--bg-dark);
        }
        
        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-title {
            color: var(--primary-color);
            margin: 0;
        }
        
        .dark-theme .modal-title {
            color: var(--secondary-color);
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--primary-color);
            transition: color 0.3s;
        }
        
        .modal-close:hover {
            color: var(--secondary-color);
        }
        
        /* Стили для страницы успеха внутри модального окна */
        .success-icon {
            font-size: 60px;
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .dark-theme .success-icon {
            color: var(--secondary-color);
        }
        
        .success-title {
            font-size: 28px;
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .dark-theme .success-title {
            color: var(--secondary-color);
        }
        
        .order-info {
            background: #fff;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin: 20px 0;
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
            width: 160px;
            color: var(--primary-color);
        }
        
        .dark-theme .info-label {
            color: var(--secondary-color);
        }
        
        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        .modal-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 18px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        
        .modal-btn:hover {
            background-color: #4a613d;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <div class="container">
        <a href="cart.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Вернуться в корзину
        </a>
        
        <h1 class="section-title">Оформление заказа</h1>
        
        <?php if (isset($_SESSION['order_error'])): ?>
            <div class="error-message">
                <?= $_SESSION['order_error'] ?>
                <?php unset($_SESSION['order_error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="checkout-container">
            <div class="checkout-section">
                <h2 class="section-title">Данные доставки</h2>
                <form method="POST" action="checkout.php">
                    <div class="form-group">
                        <label for="name">ФИО</label>
                        <input type="text" id="name" name="name" value="<?= isset($user['full_name']) ? htmlspecialchars($user['full_name']) : '' ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" name="phone" value="<?= isset($user['phone']) ? htmlspecialchars($user['phone']) : '' ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Способ доставки</label>
                        <div class="delivery-methods">
                            <input type="radio" id="delivery" name="delivery_method" value="delivery">
                            <label for="delivery" class="delivery-btn">
                                <span class="delivery-title">Доставка курьером</span>
                                <span class="delivery-desc">Бесплатная доставка по городу</span>
                            </label>
                            <input type="radio" id="pickup" name="delivery_method" value="pickup" checked>
                            <label for="pickup" class="delivery-btn">
                                <span class="delivery-title">Самовывоз</span>
                                <span class="delivery-desc">ул.Тельмана, 32, с 10:00 до 20:00</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group" id="address-group" style="display: none;">
                        <label for="address">Адрес доставки</label>
                        <input type="text" id="address" name="address">
                    </div>
                    
                    <div class="form-group">
                        <label>Способ оплаты</label>
                        <div class="payment-methods">
                            <input type="radio" id="card" name="payment_method" value="card">
                            <label for="card" class="payment-btn">
                                <i class="fas fa-credit-card"></i>
                                <span>
                                    <span class="payment-title">Оплата картой онлайн</span>
                                    <span class="payment-desc">Безопасная оплата банковской картой</span>
                                </span>
                            </label>
                            <input type="radio" id="cash" name="payment_method" value="cash" checked>
                            <label for="cash" class="payment-btn">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>
                                    <span class="payment-title">Наличными при получении</span>
                                    <span class="payment-desc">Оплата наличными в магазине</span>
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comment">Комментарий к заказу</label>
                        <textarea id="comment" name="comment" rows="4"></textarea>
                    </div>
                    
                    <button type="submit" name="place_order" class="btn">Оформить заказ</button>
                </form>
            </div>
            
            <div class="checkout-section">
                <h2 class="section-title">Ваш заказ</h2>
                <div class="order-summary">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="order-item">
                            <div>
                                <div><?= htmlspecialchars($item['product_name']) ?></div>
                                <div style="font-size: 14px; color: #666;">Артикул: <?= $item['sku'] ?></div>
                            </div>
                            <div>
                                <?= $item['quantity'] ?> × <?= number_format($item['price'], 2, '.', ' ') ?> ₽
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="order-total">
                        <div>Итого:</div>
                        <div><?= number_format($total, 2, '.', ' ') ?> ₽</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Модальное окно успешного оформления заказа -->
    <?php if ($orderSuccess && $orderData): ?>
    <div class="modal-overlay active" id="success-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Заказ успешно оформлен!</h3>
                <button class="modal-close" id="close-success-modal">&times;</button>
            </div>
            
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <p style="text-align: center;">Спасибо за ваш заказ. Мы свяжемся с вами в ближайшее время для подтверждения.</p>
            
            <div class="order-info">
                <h4>Информация о заказе</h4>
                
                <div class="info-row">
                    <div class="info-label">Номер заказа:</div>
                    <div>№<?= $orderData['order_id'] ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Дата заказа:</div>
                    <div><?= date('d.m.Y H:i', strtotime($orderData['order_date'])) ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Способ доставки:</div>
                    <div>
                        <?= $orderData['delivery_method'] === 'pickup' ? 'Самовывоз' : 'Доставка' ?>
                        <?php if ($orderData['delivery_method'] === 'delivery'): ?>
                            (<?= htmlspecialchars($orderData['delivery_address']) ?>)
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Способ оплаты:</div>
                    <div><?= $orderData['payment_method'] === 'cash' ? 'Наличными' : 'Банковской картой' ?></div>
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
                            echo $statuses[$orderData['status']] ?? $orderData['status'];
                        ?>
                    </div>
                </div>
                
                <?php if (!empty($orderData['comment'])): ?>
                    <div class="info-row">
                        <div class="info-label">Ваш комментарий:</div>
                        <div><?= htmlspecialchars($orderData['comment']) ?></div>
                    </div>
                <?php endif; ?>
                
                <div class="order-items" style="margin-top: 20px;">
                    <h4>Состав заказа (<?= $orderData['items_count'] ?>):</h4>
                    
                    <?php foreach ($orderItems as $item): ?>
                        <div class="order-item">
                            <div>
                                <div><?= htmlspecialchars($item['product_name']) ?></div>
                                <div style="font-size: 12px; color: #666;">Артикул: <?= $item['sku'] ?></div>
                            </div>
                            <div>
                                <?= $item['quantity'] ?> × <?= number_format($item['price_per_unit'], 2, '.', ' ') ?> ₽
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="order-total">
                        <div>Итого:</div>
                        <div><?= number_format($orderData['total_amount'], 2, '.', ' ') ?> ₽</div>
                    </div>
                </div>
            </div>
            
            <div class="modal-buttons">
                <a href="index.php" class="modal-btn">Вернуться в магазин</a>
                <a href="orders.php" class="modal-btn">Мои заказы</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Футер -->
    <footer class="footer">
        <?php include 'footer.php'; ?>
    </footer>

    <script>
        // Показ/скрытие поля адреса
        const deliveryMethod = document.querySelectorAll('input[name="delivery_method"]');
        const addressGroup = document.getElementById('address-group');
        
        deliveryMethod.forEach(radio => {
            radio.addEventListener('change', () => {
                addressGroup.style.display = radio.value === 'delivery' ? 'block' : 'none';
            });
        });
        
        // Выделение выбранной кнопки доставки и оплаты
        function updateSelectedRadios() {
            document.querySelectorAll('.delivery-btn').forEach(label => label.classList.remove('selected'));
            document.querySelectorAll('.payment-btn').forEach(label => label.classList.remove('selected'));
            const deliveryChecked = document.querySelector('input[name="delivery_method"]:checked');
            if (deliveryChecked) {
                document.querySelector('label[for="' + deliveryChecked.id + '"]').classList.add('selected');
            }
            const paymentChecked = document.querySelector('input[name="payment_method"]:checked');
            if (paymentChecked) {
                document.querySelector('label[for="' + paymentChecked.id + '"]').classList.add('selected');
            }
        }
        
        document.querySelectorAll('input[name="delivery_method"]').forEach(radio => {
            radio.addEventListener('change', updateSelectedRadios);
        });
        
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', updateSelectedRadios);
        });
        
        updateSelectedRadios();
        
        // Закрытие модального окна успешного заказа
        <?php if ($orderSuccess): ?>
        const successModal = document.getElementById('success-modal');
        const closeSuccessModal = document.getElementById('close-success-modal');
        
        closeSuccessModal.addEventListener('click', () => {
            successModal.classList.remove('active');
        });
        
        successModal.addEventListener('click', (e) => {
            if (e.target === successModal) {
                successModal.classList.remove('active');
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>