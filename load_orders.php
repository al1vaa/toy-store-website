<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Необходимо авторизоваться']);
    exit;
}

// Подключение к БД
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
// Фильтр заказов
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Подготовка запроса в зависимости от фильтра
$query = "
    SELECT o.*, COUNT(oi.order_item_id) as items_count 
    FROM Orders o
    LEFT JOIN OrderItems oi ON o.order_id = oi.order_id
    WHERE o.user_id = ?
";

if ($filter === 'delivered') {
    $query .= " AND o.status = 'delivered'";
}

$query .= " GROUP BY o.order_id ORDER BY o.order_date DESC";

$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Генерация HTML
$html = '';
if (count($orders) > 0) {
    foreach ($orders as $order) {
        $html .= '<div class="order-card">';
        $html .= '<div class="order-header">';
        $html .= '<div>';
        $html .= '<span class="order-id">Заказ #'.$order['order_id'].'</span>';
        $html .= '<span class="order-date">от '.date('d.m.Y H:i', strtotime($order['order_date'])).'</span>';
        $html .= '</div>';
        $html .= '<div class="order-status status-'.$order['status'].'">';
        
        $statusLabels = [
            'pending' => 'Ожидает обработки',
            'processing' => 'В обработке',
            'shipped' => 'Отправлен',
            'delivered' => 'Доставлен',
            'cancelled' => 'Отменен'
        ];
        $html .= $statusLabels[$order['status']];
        
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '<div class="order-details">';
        $html .= '<div>';
        $html .= '<div class="order-detail">';
        $html .= '<span class="order-detail-label">Способ доставки:</span>';
        $html .= $order['delivery_method'] == 'pickup' ? 'Самовывоз' : 'Доставка';
        $html .= '</div>';
        
        if ($order['delivery_method'] == 'delivery') {
            $html .= '<div class="order-detail">';
            $html .= '<span class="order-detail-label">Адрес доставки:</span>';
            $html .= htmlspecialchars($order['delivery_address']);
            $html .= '</div>';
        }
        
        $html .= '</div>';
        $html .= '<div>';
        $html .= '<div class="order-detail">';
        $html .= '<span class="order-detail-label">Способ оплаты:</span>';
        $html .= $order['payment_method'] == 'cash' ? 'Наличные' : 'Карта';
        $html .= '</div>';
        $html .= '<div class="order-detail">';
        $html .= '<span class="order-detail-label">Товаров:</span>';
        $html .= $order['items_count'];
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Получаем товары заказа
        $itemsQuery = $db->prepare("
            SELECT oi.*, p.product_name, p.sku 
            FROM OrderItems oi
            JOIN Products p ON oi.product_id = p.product_id
            WHERE oi.order_id = ?
        ");
        $itemsQuery->execute([$order['order_id']]);
        $items = $itemsQuery->fetchAll(PDO::FETCH_ASSOC);
        
        $html .= '<div class="order-items">';
        foreach ($items as $item) {
            $html .= '<div class="order-item">';
            $html .= '<div>';
            $html .= '<div>'.htmlspecialchars($item['product_name']).'</div>';
            $html .= '<div style="font-size: 12px; color: #666;">Артикул: '.$item['sku'].'</div>';
            $html .= '</div>';
            $html .= '<div>';
            $html .= $item['quantity'].' × '.number_format($item['price_per_unit'], 2, '.', ' ').' ₽';
            
            // Проверяем, можно ли оставить отзыв (только для доставленных заказов)
            if ($order['status'] === 'delivered') {
                // Проверяем, оставлял ли пользователь уже отзыв на этот товар
                $reviewCheck = $db->prepare("
                    SELECT r.* 
                    FROM Reviews r
                    WHERE r.product_id = ? AND r.user_id = ?
                ");
                $reviewCheck->execute([$item['product_id'], $_SESSION['user_id']]);
                $hasReview = $reviewCheck->fetch();
                
                if (!$hasReview) {
                    $html .= '<button class="add-review-btn" 
                            style="margin-top: 5px; padding: 5px 10px; font-size: 12px;"
                            onclick="openReviewModal('.$item['product_id'].', \''.htmlspecialchars(addslashes($item['product_name'])).'\')">
                        <i class="fas fa-star"></i> Оставить отзыв
                    </button>';
                } else {
                    $html .= '<button class="add-review-btn" 
                            style="margin-top: 5px; padding: 5px 10px; font-size: 12px; background-color: #6c757d;"
                            onclick="openEditReviewModal('.$hasReview['review_id'].', '.$item['product_id'].', \''.htmlspecialchars(addslashes($item['product_name'])).'\', '.$hasReview['rating'].', `'.htmlspecialchars(addslashes($hasReview['review_text'])).'`)">
                        <i class="fas fa-edit"></i> Редактировать отзыв
                    </button>';
                }
            }
            
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
        
        $html .= '<div class="order-total">';
        $html .= 'Итого: '.number_format($order['total_amount'], 2, '.', ' ').' ₽';
        $html .= '</div>';
        
        if (!empty($order['comment'])) {
            $html .= '<div style="margin-top: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 4px;">';
            $html .= '<strong>Комментарий:</strong> '.htmlspecialchars($order['comment']);
            $html .= '</div>';
        }
        
        $html .= '</div>';
    }
} else {
    $html = '<div class="empty-orders">';
    $html .= '<i class="fas fa-clipboard-list" style="font-size: 50px; margin-bottom: 20px;"></i>';
    $html .= '<h3>Нет заказов</h3>';
    $html .= '</div>';
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'html' => $html]);
?>