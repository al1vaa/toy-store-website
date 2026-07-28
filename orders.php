<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=orders");
    exit;
}

// Подключение к базе данных
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
// Получение информации о пользователе
$userQuery = $db->prepare("SELECT * FROM Users WHERE user_id = ?");
$userQuery->execute([$_SESSION['user_id']]);
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

// Параметры пагинации
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Исправленный запрос (без кавычек вокруг LIMIT и OFFSET)
$query = "
    SELECT 
        o.*, 
        u.first_name, 
        u.last_name, 
        (SELECT COUNT(*) FROM OrderItems oi WHERE oi.order_id = o.order_id) AS items_count
    FROM Orders o
    JOIN Users u ON o.user_id = u.user_id
    ORDER BY o.order_date DESC
    LIMIT :limit OFFSET :offset
";


$stmt = $db->prepare($query);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Проверка прав администратора
$isAdmin = isset($_SESSION['user_id']) && $_SESSION['role_id'] == 777;
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BirchBark - Мои заказы</title>
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
        
        /* Стили для заказов */
        .orders-section {
            padding: 40px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: var(--primary-color);
        }
        
        .dark-theme .section-title {
            color: var(--secondary-color);
        }
        
        .order-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .order-card {
            background: white;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .dark-theme .order-card {
            background: #333;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .dark-theme .order-header {
            border-bottom-color: #555;
        }
        
        .order-id {
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .dark-theme .order-id {
            color: var(--secondary-color);
        }
        
        .order-date {
            color: #666;
        }
        
        .dark-theme .order-date {
            color: #aaa;
        }
        
        .order-status {
            padding: 5px 10px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .status-shipped {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-delivered {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .order-detail {
            margin-bottom: 5px;
        }
        
        .order-detail-label {
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .dark-theme .order-detail-label {
            color: var(--secondary-color);
        }
        
        .order-items {
            margin-top: 15px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .dark-theme .order-item {
            border-bottom-color: #555;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
            margin-top: 15px;
        }
        
        .empty-orders {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .dark-theme .empty-orders {
            color: #aaa;
        }
        
        .empty-orders-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 14px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .empty-orders-btn:hover {
            background-color: #4a613d;
        }
        
        /* Пагинация */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 10px;
        }
        
        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 14px;
            text-decoration: none;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            transition: all 0.3s;
        }
        
        .pagination a:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .pagination .current {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
        }
        
        .pagination .disabled {
            color: #aaa;
            border-color: #aaa;
            pointer-events: none;
        }
        
        /* Стили для модального окна отзыва */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
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
            padding: 30px;
            border-radius: 18px;
            width: 90%;
            max-width: 500px;
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
        
        /* Стили для рейтинга */
        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            margin: 10px 0;
        }
        
        .rating input {
            display: none;
        }
        
        .rating label {
            color: #ccc;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: var(--secondary-color);
        }
        
        /* Стили для формы отзыва */
        .review-form textarea {
            width: 100%;
            padding: 10px;
            border-radius: 14px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            font-family: inherit;
            resize: none;
            min-height: 100px;
        }
        
        .dark-theme .review-form textarea {
            background-color: #444;
            border-color: #555;
            color: white;
        }
        
        .review-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 14px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .review-submit:hover {
            background-color: #4a613d;
        }
        
        /* Кнопка для отзыва */
        .add-review-btn {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 12px;
        }
        
        .add-review-btn:hover {
            background-color: #c09552;
        }
        
        /* Уведомления */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--primary-color);
            color: white;
            padding: 15px 25px;
            border-radius: 14px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
            transform: translateX(150%);
            transition: transform 0.3s ease-in-out;
        }
        
        .notification.active {
            transform: translateX(0);
        }
        
        .notification i {
            font-size: 20px;
        }
        
        .notification.error {
            background-color: #dc3545;
        }
        
        .notification.success {
            background-color: #28a745;
        }
        
        .notification.warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        /* Подтверждение действий */
        .confirmation-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .confirmation-modal.active {
            opacity: 1;
            visibility: visible;
        }
        
        .confirmation-content {
            background-color: var(--bg-light);
            padding: 30px;
            border-radius: 18px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transform: translateY(-20px);
            transition: transform 0.3s;
        }
        
        .dark-theme .confirmation-content {
            background-color: var(--bg-dark);
        }
        
        .confirmation-modal.active .confirmation-content {
            transform: translateY(0);
        }
        
        .confirmation-message {
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .confirmation-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .confirm-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 14px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .confirm-btn:hover {
            background-color: #4a613d;
        }
        
        .cancel-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 14px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .cancel-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    
    <!-- Секция с заказами -->
    <section class="orders-section">
        <div class="container">
            <h2 class="section-title">Мои заказы</h2>
            
            <?php if (count($orders) > 0): ?>
                <div class="order-list">
                    <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div>
                                    <span class="order-id">Заказ #<?= $order['order_id'] ?></span>
                                    <span class="order-date">от <?= date('d.m.Y H:i', strtotime($order['order_date'])) ?></span>
                                </div>
                                <div class="order-status status-<?= $order['status'] ?>">
                                    <?php 
                                        $statusLabels = [
                                            'pending' => 'Ожидает обработки',
                                            'processing' => 'В обработке',
                                            'shipped' => 'Отправлен',
                                            'delivered' => 'Доставлен',
                                            'cancelled' => 'Отменен'
                                        ];
                                        echo $statusLabels[$order['status']];
                                    ?>
                                </div>
                            </div>
                            
                            <div class="order-details">
                                <div>
                                    <div class="order-detail">
                                        <span class="order-detail-label">Способ доставки:</span>
                                        <?= $order['delivery_method'] == 'pickup' ? 'Самовывоз' : 'Доставка' ?>
                                    </div>
                                    <?php if ($order['delivery_method'] == 'delivery'): ?>
                                        <div class="order-detail">
                                            <span class="order-detail-label">Адрес доставки:</span>
                                            <?= htmlspecialchars($order['delivery_address']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="order-detail">
                                        <span class="order-detail-label">Способ оплаты:</span>
                                        <?= $order['payment_method'] == 'cash' ? 'Наличные' : 'Карта' ?>
                                    </div>
                                    <div class="order-detail">
                                        <span class="order-detail-label">Товаров:</span>
                                        <?= $order['items_count'] ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="order-items">
                                <?php 
                                    // Получаем товары заказа
                                    $itemsQuery = $db->prepare("
                                        SELECT oi.*, p.product_name, p.sku 
                                        FROM OrderItems oi
                                        JOIN Products p ON oi.product_id = p.product_id
                                        WHERE oi.order_id = ?
                                    ");
                                    $itemsQuery->execute([$order['order_id']]);
                                    $items = $itemsQuery->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    foreach ($items as $item): 
                                        // Проверяем, был ли заказ доставлен
                                        $isDelivered = $order['status'] === 'delivered';
                                        
                                        // Проверяем, оставлял ли пользователь уже отзыв на этот товар
                                        $reviewCheck = $db->prepare("
                                            SELECT r.* 
                                            FROM Reviews r
                                            WHERE r.product_id = ? AND r.user_id = ?
                                        ");
                                        $reviewCheck->execute([$item['product_id'], $_SESSION['user_id']]);
                                        $hasReview = $reviewCheck->fetch();
                                ?>
                                    <div class="order-item">
                                        <div>
                                            <div><?= htmlspecialchars($item['product_name']) ?></div>
                                            <div style="font-size: 12px; color: #666;">Артикул: <?= $item['sku'] ?></div>
                                        </div>
                                        <div>
                                            <?= $item['quantity'] ?> × <?= number_format($item['price_per_unit'], 2, '.', ' ') ?> ₽
                                            <?php if ($isDelivered): ?>
                                                <?php if (!$hasReview): ?>
                                                    <button class="add-review-btn" 
                                                            style="margin-top: 5px; padding: 5px 10px; font-size: 12px;"
                                                            onclick="openReviewModal(<?= $item['product_id'] ?>, '<?= htmlspecialchars(addslashes($item['product_name'])) ?>')">
                                                        <i class="fas fa-star"></i> Оставить отзыв
                                                    </button>
                                                <?php else: ?>
                                                    <button class="add-review-btn" 
                                                            style="margin-top: 5px; padding: 5px 10px; font-size: 12px; background-color: #6c757d;"
                                                            onclick="openEditReviewModal(<?= $hasReview['review_id'] ?>, <?= $item['product_id'] ?>, '<?= htmlspecialchars(addslashes($item['product_name'])) ?>', <?= $hasReview['rating'] ?>, `<?= htmlspecialchars(addslashes($hasReview['review_text'])) ?>`)">
                                                        <i class="fas fa-edit"></i> Редактировать отзыв
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="order-total">
                                Итого: <?= number_format($order['total_amount'], 2, '.', ' ') ?> ₽
                            </div>
                            
                            <?php if (!empty($order['comment'])): ?>
                                <div style="margin-top: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 4px;">
                                    <strong>Комментарий:</strong> <?= htmlspecialchars($order['comment']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
        <!-- Пагинация -->
        <div class="pagination">
            <?php
            // Получаем общее количество заказов
            $totalOrdersStmt = $db->query("SELECT COUNT(*) FROM Orders");
            $totalOrders = $totalOrdersStmt->fetchColumn();
        
            $totalPages = ceil($totalOrders / $limit);
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            ?>
        
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">&laquo; Назад</a>
            <?php else: ?>
                <span class="disabled">&laquo; Назад</span>
            <?php endif; ?>
        
            <?php
            if ($startPage > 1) {
                echo '<a href="?page=1">1</a>';
                if ($startPage > 2) {
                    echo '<span>...</span>';
                }
            }
        
            for ($i = $startPage; $i <= $endPage; $i++) {
                if ($i == $page) {
                    echo '<span class="current">' . $i . '</span>';
                } else {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
            }
        
            if ($endPage < $totalPages) {
                if ($endPage < $totalPages - 1) {
                    echo '<span>...</span>';
                }
                echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
            }
            ?>
        
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>">Вперед &raquo;</a>
            <?php else: ?>
                <span class="disabled">Вперед &raquo;</span>
            <?php endif; ?>
        </div>

                </div>
            <?php else: ?>
                <div class="empty-orders">
                    <i class="fas fa-clipboard-list" style="font-size: 50px; margin-bottom: 20px;"></i>
                    <h3>У вас пока нет заказов</h3>
                    <p>Перейдите в каталог, чтобы сделать первый заказ</p>
                    <button onclick="window.location.href='catalog.php'" class="empty-orders-btn">
                        <i class="fas fa-store"></i> Перейти в каталог
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Модальное окно для отзыва -->
    <div class="modal-overlay" id="review-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Оставить отзыв для <span id="review-product-name"></span></h3>
                <button class="modal-close" id="close-review-modal">&times;</button>
            </div>
            <form class="review-form" id="review-form">
                <input type="hidden" name="product_id" id="review-product-id">
                <div class="rating">
                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5">★</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                </div>
                <textarea name="review_text" placeholder="Напишите ваш отзыв..." required></textarea>
                <button type="submit" class="review-submit">Отправить отзыв</button>
            </form>
        </div>
    </div>
    
    <!-- Модальное окно для редактирования отзыва -->
    <div class="modal-overlay" id="edit-review-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Редактировать отзыв для <span id="edit-review-product-name"></span></h3>
                <button class="modal-close" id="close-edit-review-modal">&times;</button>
            </div>
            <form class="review-form" id="edit-review-form">
                <input type="hidden" name="review_id" id="edit-review-id">
                <input type="hidden" name="product_id" id="edit-review-product-id">
                <div class="rating">
                    <input type="radio" id="edit-star5" name="rating" value="5"><label for="edit-star5">★</label>
                    <input type="radio" id="edit-star4" name="rating" value="4"><label for="edit-star4">★</label>
                    <input type="radio" id="edit-star3" name="rating" value="3"><label for="edit-star3">★</label>
                    <input type="radio" id="edit-star2" name="rating" value="2"><label for="edit-star2">★</label>
                    <input type="radio" id="edit-star1" name="rating" value="1"><label for="edit-star1">★</label>
                </div>
                <textarea name="review_text" id="edit-review-text" placeholder="Напишите ваш отзыв..." required></textarea>
                <div style="display: flex; justify-content: space-between;">
                    <button type="submit" class="review-submit">Сохранить изменения</button>
                    <button type="button" class="review-submit" style="background-color: #dc3545;" id="delete-review-btn">Удалить отзыв</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Модальное окно подтверждения -->
    <div class="confirmation-modal" id="confirmation-modal">
        <div class="confirmation-content">
            <div class="confirmation-message" id="confirmation-message"></div>
            <div class="confirmation-buttons">
                <button class="cancel-btn" id="cancel-confirmation">Отмена</button>
                <button class="confirm-btn" id="confirm-action">Подтвердить</button>
            </div>
        </div>
    </div>
    
    <!-- Уведомление -->
    <div class="notification" id="notification">
        <i class="fas fa-check-circle"></i>
        <span id="notification-message"></span>
    </div>
    
    <!-- Футер -->
    <footer class="footer">
        <?php include 'footer.php'; ?>
    </footer>

    <script>
 // Модальное окно для отзывов
    const reviewModal = document.getElementById('review-modal');
    const editReviewModal = document.getElementById('edit-review-modal');
    const closeReviewModal = document.getElementById('close-review-modal');
    const closeEditReviewModal = document.getElementById('close-edit-review-modal');
    const reviewForm = document.getElementById('review-form');
    const editReviewForm = document.getElementById('edit-review-form');
    const deleteReviewBtn = document.getElementById('delete-review-btn');
    const notification = document.getElementById('notification');
    const notificationMessage = document.getElementById('notification-message');
    const confirmationModal = document.getElementById('confirmation-modal');
    const confirmationMessage = document.getElementById('confirmation-message');
    const confirmActionBtn = document.getElementById('confirm-action');
    const cancelConfirmationBtn = document.getElementById('cancel-confirmation');
    
    let currentAction = null;
    let currentReviewId = null;
    
    // Открытие модального окна для нового отзыва
    function openReviewModal(productId, productName) {
        // Очищаем форму перед использованием
        reviewForm.reset();
        
        // Устанавливаем значения
        document.getElementById('review-product-id').value = productId;
        document.getElementById('review-product-name').textContent = productName;
        
        // Показываем модальное окно
        reviewModal.classList.add('active');
    }
    
    // Открытие модального окна для редактирования отзыва
    function openEditReviewModal(reviewId, productId, productName, rating, reviewText) {
        document.getElementById('edit-review-id').value = reviewId;
        document.getElementById('edit-review-product-id').value = productId;
        document.getElementById('edit-review-product-name').textContent = productName;
        document.getElementById('edit-review-text').value = reviewText;
        
        // Устанавливаем выбранный рейтинг
        document.querySelectorAll('#edit-review-form input[name="rating"]').forEach(input => {
            input.checked = (parseInt(input.value) === rating);
        });
        
        editReviewModal.classList.add('active');
        currentReviewId = reviewId;
    }
    
    // Закрытие модальных окон
    closeReviewModal.addEventListener('click', () => {
        reviewModal.classList.remove('active');
    });
    
    closeEditReviewModal.addEventListener('click', () => {
        editReviewModal.classList.remove('active');
    });
    
    [reviewModal, editReviewModal].forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
    
    // Показать уведомление
    function showNotification(message, type = 'success') {
        notificationMessage.textContent = message;
        notification.className = 'notification ' + type;
        notification.querySelector('i').className = type === 'error' ? 'fas fa-exclamation-circle' : 
                                                    type === 'warning' ? 'fas fa-exclamation-triangle' : 
                                                    'fas fa-check-circle';
        notification.classList.add('active');
        
        setTimeout(() => {
            notification.classList.remove('active');
        }, 3000);
    }
    
    // Показать подтверждение
    function showConfirmation(message, action) {
        confirmationMessage.textContent = message;
        currentAction = action;
        confirmationModal.classList.add('active');
    }
    
    // Закрыть подтверждение
    function closeConfirmation() {
        confirmationModal.classList.remove('active');
        currentAction = null;
    }
    
    // Обработчики для модального окна подтверждения
    confirmActionBtn.addEventListener('click', () => {
        if (currentAction === 'delete') {
            deleteReview();
        } else if (currentAction === 'update') {
            submitEditReviewForm();
        }
        closeConfirmation();
    });
    
    cancelConfirmationBtn.addEventListener('click', closeConfirmation);
    
    confirmationModal.addEventListener('click', (e) => {
        if (e.target === confirmationModal) {
            closeConfirmation();
        }
    });
    
// Отправка нового отзыва
reviewForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(reviewForm);
    const data = {
        action: 'add',
        product_id: formData.get('product_id'),
        rating: formData.get('rating'),
        review_text: formData.get('review_text')
    };
    
    try {
        const response = await fetch('reviews.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Отзыв успешно добавлен!');
            reviewModal.classList.remove('active');
            reviewForm.reset();
            // Обновляем страницу, чтобы показать новый отзыв
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Ошибка: ' + (result.message || 'Неизвестная ошибка'), 'error');
        }
    } catch (error) {
        console.error('Ошибка:', error);
        showNotification('Произошла ошибка при отправке отзыва', 'error');
    }
});

// Обработчик для кнопки удаления отзыва
deleteReviewBtn.addEventListener('click', (e) => {
    e.preventDefault();
    showConfirmation('Вы уверены, что хотите удалить этот отзыв?', 'delete');
});

// Обработчик для формы редактирования отзыва
editReviewForm.addEventListener('submit', (e) => {
    e.preventDefault();
    showConfirmation('Сохранить изменения в отзыве?', 'update');
});

// Функция для подтвержденного удаления отзыва
async function deleteReview() {
    const reviewId = document.getElementById('edit-review-id').value;
    const data = {
        action: 'delete',
        review_id: reviewId
    };
    
    try {
        const response = await fetch('reviews.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Отзыв успешно удален!');
            editReviewModal.classList.remove('active');
            // Обновляем страницу, чтобы убрать удаленный отзыв
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Ошибка: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Ошибка:', error);
        showNotification('Произошла ошибка при удалении отзыва', 'error');
    }
}

// Функция для подтвержденного обновления отзыва
async function submitEditReviewForm() {
    const formData = new FormData(editReviewForm);
    const data = {
        action: 'update',
        review_id: formData.get('review_id'),
        product_id: formData.get('product_id'),
        rating: formData.get('rating'),
        review_text: formData.get('review_text')
    };
    
    try {
        const response = await fetch('reviews.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Отзыв успешно обновлен!');
            editReviewModal.classList.remove('active');
            // Обновляем страницу, чтобы показать изменения
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Ошибка: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Ошибка:', error);
        showNotification('Произошла ошибка при обновлении отзыва', 'error');
    }
}
</script>
</body>
</html>