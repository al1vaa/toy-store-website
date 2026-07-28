<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 777) {
    header("Location: login.php");
    exit;
}

$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
// Обновление статуса заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    $comment = trim($_POST['comment']);
    
    $stmt = $db->prepare("UPDATE Orders SET status = ?, comment = ? WHERE order_id = ?");
    $stmt->execute([$status, $comment, $order_id]);
    
    $_SESSION['notification'] = "Заказ #$order_id успешно обновлен";
    header("Location: manage_orders.php");
    exit;
}

// Удаление заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $order_id = (int)$_POST['order_id'];
    
    try {
        $db->beginTransaction();
        
        // Удаляем товары заказа
        $stmt = $db->prepare("DELETE FROM OrderItems WHERE order_id = ?");
        $stmt->execute([$order_id]);
        
        // Удаляем сам заказ
        $stmt = $db->prepare("DELETE FROM Orders WHERE order_id = ?");
        $stmt->execute([$order_id]);
        
        $db->commit();
        
        $_SESSION['notification'] = "Заказ #$order_id успешно удален";
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['notification'] = "Ошибка при удалении заказа: " . $e->getMessage();
    }
    
    header("Location: manage_orders.php");
    exit;
}

// Получаем все заказы
$orders = $db->query("
    SELECT o.*, u.first_name, u.last_name, u.phone 
    FROM Orders o
    JOIN Users u ON o.user_id = u.user_id
    ORDER BY o.order_date DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Получаем товары для каждого заказа
foreach ($orders as &$order) {
    $stmt = $db->prepare("
        SELECT oi.*, p.product_name 
        FROM OrderItems oi
        JOIN Products p ON oi.product_id = p.product_id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order['order_id']]);
    $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
unset($order);

// Группируем заказы по статусам
$statusGroups = [
    'pending' => [],
    'processing' => [],
    'shipped' => [],
    'delivered' => [],
    'cancelled' => []
];

foreach ($orders as $order) {
    $statusGroups[$order['status']][] = $order;
}

$statusLabels = [
    'pending' => 'Ожидает обработки',
    'processing' => 'В обработке',
    'shipped' => 'Отправлен',
    'delivered' => 'Доставлен',
    'cancelled' => 'Отменен'
];
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление заказами | BirchBark</title>
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
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-title {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .dark-theme .page-title {
            color: var(--secondary-color);
        }
        
        .orders-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .status-column {
            background: white;
            border-radius: 18px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            padding: 15px;
            height: 80vh; /* Фиксированная высота колонки */
            display: flex;
            flex-direction: column;
        }
        
        .dark-theme .status-column {
            background: #333;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }
        
        .status-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            flex-shrink: 0; /* Чтобы заголовок не сжимался */
        }
        
        .dark-theme .status-header {
            border-bottom-color: #444;
        }
        
        .status-title {
            font-weight: bold;
            font-size: 18px;
            margin: 0;
        }
        
        .status-count {
            background-color: #eee;
            color: #333;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        
        .dark-theme .status-count {
            background-color: #444;
            color: white;
        }
        
        .orders-list {
            flex-grow: 1; /* Занимает все доступное пространство */
            overflow-y: auto; /* Включаем вертикальную прокрутку */
            padding-right: 5px; /* Чтобы контент не прилипал к скроллбару */
            display: grid;
            gap: 15px;
        }
        
        /* Стилизация скроллбара */
        .orders-list::-webkit-scrollbar {
            width: 8px;
        }
        
        .orders-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .orders-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .orders-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        .dark-theme .orders-list::-webkit-scrollbar-track {
            background: #444;
        }
        
        .dark-theme .orders-list::-webkit-scrollbar-thumb {
            background: #666;
        }
        
        .dark-theme .orders-list::-webkit-scrollbar-thumb:hover {
            background: #777;
        }
        
        .order-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 15px;
            border-left: 4px solid var(--primary-color);
            transition: transform 0.2s;
            flex-shrink: 0; /* Чтобы карточки не сжимались */
        }
        
        .dark-theme .order-card {
            background: #444;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            border-left-color: var(--secondary-color);
        }
        
        .order-card:hover {
            transform: translateY(-3px);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .order-id {
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .dark-theme .order-id {
            color: var(--secondary-color);
        }
        
        .order-date {
            font-size: 12px;
            color: #777;
        }
        
        .dark-theme .order-date {
            color: #aaa;
        }
        
        .order-customer {
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .order-items {
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        
        .order-total {
            font-weight: bold;
            text-align: right;
            margin: 10px 0;
            font-size: 15px;
        }
        
        .order-form {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        
        .dark-theme .order-form {
            border-top-color: #555;
        }
        
        .form-group {
            margin-bottom: 10px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 3px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .form-control {
            width: 90%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            resize: none
        }
        
        .dark-theme .form-control {
            background-color: #555;
            border-color: #666;
            color: white;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
            flex: 1;
        }
        
        .btn-submit:hover {
            background-color: #4a613d;
        }
        
        .btn-delete {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        
        .btn-delete:hover {
            background-color: #c0392b;
        }
        
        /* Цвета статусов */
        .status-pending { border-left-color: #f39c12; }
        .status-processing { border-left-color: #3498db; }
        .status-shipped { border-left-color: #9b59b6; }
        .status-delivered { border-left-color: #2ecc71; }
        .status-cancelled { border-left-color: #e74c3c; }
        
        .dark-theme .status-pending { border-left-color: #f39c12; }
        .dark-theme .status-processing { border-left-color: #3498db; }
        .dark-theme .status-shipped { border-left-color: #9b59b6; }
        .dark-theme .status-delivered { border-left-color: #2ecc71; }
        .dark-theme .status-cancelled { border-left-color: #e74c3c; }
        
        .empty-state {
            text-align: center;
            padding: 10px;
            color: #777;
            font-size: 14px;
            margin: auto;
        }
        
        .dark-theme .empty-state {
            color: #aaa;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <h1 class="page-title">Управление заказами</h1>
    
    <div class="orders-container">
        <?php foreach ($statusGroups as $status => $ordersInGroup): ?>
            <div class="status-column">
                <div class="status-header">
                    <h3 class="status-title"><?= $statusLabels[$status] ?></h3>
                    <span class="status-count"><?= count($ordersInGroup) ?></span>
                </div>
                
                <div class="orders-list">
                    <?php foreach ($ordersInGroup as $order): ?>
                        <div class="order-card status-<?= $status ?>">
                            <div class="order-header">
                                <span class="order-id">#<?= $order['order_id'] ?></span>
                                <span class="order-date"><?= date('d.m.Y H:i', strtotime($order['order_date'])) ?></span>
                            </div>
                            
                            <div class="order-customer">
                                <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?><br>
                                Телефон: <?= htmlspecialchars($order['phone']) ?>
                            </div>
                            
                            <div class="order-items">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="order-item">
                                        <span><?= htmlspecialchars($item['product_name']) ?> (x<?= $item['quantity'] ?>)</span>
                                        <span><?= number_format($item['price_per_unit'] * $item['quantity'], 2, '.', ' ') ?> ₽</span>
                                    </div>
                                <?php endforeach; ?>
                                <div class="order-total">
                                    Итого: <?= number_format($order['total_amount'], 2, '.', ' ') ?> ₽
                                </div>
                            </div>
                            
                            <form method="POST" class="order-form">
                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                
                                <div class="form-group">
                                    <label for="status-<?= $order['order_id'] ?>">Статус</label>
                                    <select id="status-<?= $order['order_id'] ?>" name="status" class="form-control" required>
                                        <?php foreach ($statusLabels as $key => $label): ?>
                                            <option value="<?= $key ?>" <?= $order['status'] == $key ? 'selected' : '' ?>><?= $label ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="comment-<?= $order['order_id'] ?>">Комментарий</label>
                                    <textarea id="comment-<?= $order['order_id'] ?>" name="comment" class="form-control" rows="2"><?= htmlspecialchars($order['comment']) ?></textarea>
                                </div>
                                
                                <div class="btn-group">
                                    <button type="submit" name="update_order" class="btn-submit">Обновить</button>
                                    <button type="submit" name="delete_order" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить этот заказ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($ordersInGroup)): ?>
                        <div style="text-align: center; padding: 10px; color: #777; font-size: 14px;">
                            Нет заказов в этом статусе
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>