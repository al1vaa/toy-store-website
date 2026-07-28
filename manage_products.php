<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 777) {
    header("Location: login.php");
    exit;
}

$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');

// Удаление товара через AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = (int)$_POST['product_id'];
    $response = ['success' => false, 'message' => ''];
    
    try {
        $db->beginTransaction();
        
        // 1. Получаем информацию о товаре
        $stmt = $db->prepare("SELECT specification_id, gallery_id FROM Products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if ($product) {
            // 2. Удаляем связанные записи
            
            // Удаляем из корзин
            $stmt = $db->prepare("DELETE FROM CartItems WHERE product_id = ?");
            $stmt->execute([$product_id]);
            
            // Удаляем из заказов
            $stmt = $db->prepare("DELETE FROM OrderItems WHERE product_id = ?");
            $stmt->execute([$product_id]);
            
            // Удаляем отзывы
            $stmt = $db->prepare("DELETE FROM Reviews WHERE product_id = ?");
            $stmt->execute([$product_id]);
            
            // Удаляем из инвентаря
            $stmt = $db->prepare("DELETE FROM Inventory WHERE product_id = ?");
            $stmt->execute([$product_id]);
            
            // 3. Удаляем сам товар
            $stmt = $db->prepare("DELETE FROM Products WHERE product_id = ?");
            $stmt->execute([$product_id]);
            
            // 4. Удаляем спецификацию, если она больше не используется
            if ($product['specification_id']) {
                $stmt = $db->prepare("SELECT COUNT(*) FROM Products WHERE specification_id = ?");
                $stmt->execute([$product['specification_id']]);
                if ($stmt->fetchColumn() == 0) {
                    $stmt = $db->prepare("DELETE FROM ProductSpecifications WHERE specification_id = ?");
                    $stmt->execute([$product['specification_id']]);
                }
            }
            
            // 5. Удаляем галерею, если она больше не используется
            if ($product['gallery_id']) {
                $stmt = $db->prepare("SELECT COUNT(*) FROM Products WHERE gallery_id = ?");
                $stmt->execute([$product['gallery_id']]);
                if ($stmt->fetchColumn() == 0) {
                    $stmt = $db->prepare("DELETE FROM Galleries WHERE gallery_id = ?");
                    $stmt->execute([$product['gallery_id']]);
                }
            }
            
            $db->commit();
            $response['success'] = true;
            $response['message'] = "Товар успешно удален";
        } else {
            $response['message'] = "Товар не найден";
        }
    } catch (PDOException $e) {
        $db->rollBack();
        $response['message'] = "Ошибка при удалении товара: " . $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Параметры сортировки
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'product_id';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Допустимые поля для сортировки
$allowed_sort = ['product_id', 'product_name', 'price', 'stock_quantity', 'category_name', 'manufacturer_name'];
if (!in_array($sort, $allowed_sort)) {
    $sort = 'product_id';
}

// Допустимые направления сортировки
$allowed_order = ['ASC', 'DESC'];
if (!in_array($order, $allowed_order)) {
    $order = 'DESC';
}

// Поиск товаров
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_condition = '';
$search_params = [];

if (!empty($search)) {
    $search_condition = " AND (p.product_name LIKE :search OR p.sku LIKE :search OR m.manufacturer_name LIKE :search OR c.category_name LIKE :search)";
    $search_params = [':search' => "%$search%"];
}

// Получаем список товаров для отображения с учетом сортировки и поиска
try {
    $sql = "
        SELECT p.*, c.category_name, m.manufacturer_name 
        FROM Products p
        LEFT JOIN Categories c ON p.category_id = c.category_id
        LEFT JOIN Manufacturers m ON p.manufacturer_id = m.manufacturer_id
        WHERE 1=1 $search_condition
        ORDER BY $sort $order
    ";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($search_params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
    error_log("Ошибка при получении списка товаров: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление товарами | BirchBark</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-title {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 30px;
        }
        
        .dark-theme .page-title {
            color: var(--secondary-color);
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #4a613d;
        }
        
        .dark-theme .btn-primary {
            background-color: var(--secondary-color);
            color: #333;
        }
        
        .dark-theme .btn-primary:hover {
            background-color: #e0b873;
        }
        
        .products-list {
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .dark-theme th, 
        .dark-theme td {
            border-bottom-color: #444;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: 600;
            cursor: pointer;
            position: relative;
        }
        
        .dark-theme th {
            background-color: #333;
            color: white;
        }
        
        th:hover {
            background-color: #e0e0e0;
        }
        
        .dark-theme th:hover {
            background-color: #444;
        }
        
        .sort-icon {
            margin-left: 5px;
            color: #888;
        }
        
        .dark-theme .sort-icon {
            color: #aaa;
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        .dark-theme tr:hover {
            background-color: #3a3a3a;
        }
        
        .action-btns {
            display: flex;
            gap: 10px;
        }
        
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 14px;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background-color: #3498db;
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #2980b9;
        }
        
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c0392b;
        }
        
        .product-link {
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .dark-theme .product-link {
            color: var(--text-dark);
        }
        
        .product-link:hover {
            color: var(--primary-color);
        }
        
        .dark-theme .product-link:hover {
            color: var(--secondary-color);
        }

        /* Стили для модального окна подтверждения */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 14px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transform: translateY(-20px);
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }
        
        .dark-theme .modal-content {
            background-color: #333;
            color: white;
        }
        
        .modal-title {
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .dark-theme .modal-title {
            color: var(--secondary-color);
        }
        
        .modal-message {
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }
        
        .modal-btn {
            padding: 10px 20px;
            border-radius: 14px;
            cursor: pointer;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .modal-btn-cancel {
            background-color: #f5f5f5;
            color: #333;
        }
        
        .dark-theme .modal-btn-cancel {
            background-color: #444;
            color: white;
        }
        
        .modal-btn-confirm {
            background-color: #e74c3c;
            color: white;
        }
        
        .modal-btn-confirm:hover {
            background-color: #c0392b;
        }
        
        /* Уведомления */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 5px 5px;
            border-radius: 14px;
            background-color: #2ecc71;
            color: white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transform: translateX(120%);
            transition: transform 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.error {
            background-color: #e74c3c;
        }
        
        .notification-icon {
            margin-right: 10px;
            font-size: 20px;
        }
        
        /* Блокировка интерфейса при загрузке */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            display: none;
        }
        
        .loading-spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Поисковая форма */
        .search-form {
            margin: 20px 0;
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 14px;
            font-family: 'Lora', serif;
            font-size: 16px;
        }
        
        .dark-theme .search-input {
            background-color: #444;
            color: white;
            border-color: #555;
        }
        
        .search-btn {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .search-btn:hover {
            background-color: #4a613d;
        }
        
        .dark-theme .search-btn {
            background-color: var(--secondary-color);
            color: #333;
        }
        
        .dark-theme .search-btn:hover {
            background-color: #e0b873;
        }
        
        /* Стили для кнопки сброса поиска */
        .reset-search {
            display: inline-block;
            margin-left: 10px;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
        }
        
        .dark-theme .reset-search {
            color: var(--secondary-color);
        }
        
        .reset-search:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <h1 class="page-title">Управление товарами</h1>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="add_product.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Добавить товар
        </a>
        
        <!-- Форма поиска -->
        <form method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Поиск товаров..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i> Найти
            </button>
            <?php if (!empty($search)): ?>
                <a href="manage_products.php" class="reset-search">Сбросить</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="products-list">
        <table>
            <thead>
                <tr>
                    <th onclick="sortTable('product_id')">
                        ID 
                        <?php if ($sort == 'product_id'): ?>
                            <i class="fas fa-sort-<?= strtolower($order) ?> sort-icon"></i>
                        <?php else: ?>
                            <i class="fas fa-sort sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th onclick="sortTable('product_name')">
                        Название 
                        <?php if ($sort == 'product_name'): ?>
                            <i class="fas fa-sort-<?= strtolower($order) ?> sort-icon"></i>
                        <?php else: ?>
                            <i class="fas fa-sort sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th onclick="sortTable('category_name')">
                        Категория 
                        <?php if ($sort == 'category_name'): ?>
                            <i class="fas fa-sort-<?= strtolower($order) ?> sort-icon"></i>
                        <?php else: ?>
                            <i class="fas fa-sort sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th onclick="sortTable('manufacturer_name')">
                        Производитель 
                        <?php if ($sort == 'manufacturer_name'): ?>
                            <i class="fas fa-sort-<?= strtolower($order) ?> sort-icon"></i>
                        <?php else: ?>
                            <i class="fas fa-sort sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th onclick="sortTable('price')">
                        Цена 
                        <?php if ($sort == 'price'): ?>
                            <i class="fas fa-sort-<?= strtolower($order) ?> sort-icon"></i>
                        <?php else: ?>
                            <i class="fas fa-sort sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th onclick="sortTable('stock_quantity')">
                        Количество 
                        <?php if ($sort == 'stock_quantity'): ?>
                            <i class="fas fa-sort-<?= strtolower($order) ?> sort-icon"></i>
                        <?php else: ?>
                            <i class="fas fa-sort sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Товары не найдены</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <tr data-product-id="<?= $product['product_id'] ?>">
                        <td><?= $product['product_id'] ?></td>
                        <td>
                            <a href="product.php?id=<?= $product['product_id'] ?>" class="product-link">
                                <?= htmlspecialchars($product['product_name']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($product['category_name'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($product['manufacturer_name'] ?? '—') ?></td>
                        <td><?= number_format($product['price'], 2, '.', ' ') ?> ₽</td>
                        <td><?= $product['stock_quantity'] ?></td>
                        <td>
                            <div class="action-btns">
                                <a href="edit_product.php?id=<?= $product['product_id'] ?>" class="btn-icon btn-edit" title="Редактировать">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn-icon btn-delete" 
                                        title="Удалить"
                                        onclick="confirmDelete(<?= $product['product_id'] ?>, '<?= htmlspecialchars(addslashes($product['product_name'])) ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <h3 class="modal-title">Подтверждение удаления</h3>
        <p class="modal-message" id="deleteMessage">Вы уверены, что хотите удалить товар "<span id="productName"></span>"?</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Отмена</button>
            <button class="modal-btn modal-btn-confirm" id="confirmDeleteBtn">Удалить</button>
        </div>
    </div>
</div>

<!-- Уведомление об успешном удалении -->
<div class="notification" id="successNotification">
    <i class="fas fa-check-circle notification-icon"></i>
    <span id="notificationMessage"></span>
</div>

<!-- Блокировка интерфейса при загрузке -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<?php include 'footer.php'; ?>

<script>
    // Подтверждение удаления
    let productToDelete = null;
    
    function confirmDelete(id, name) {
        productToDelete = id;
        document.getElementById('productName').textContent = name;
        document.getElementById('deleteModal').classList.add('active');
    }
    
    function closeModal() {
        document.getElementById('deleteModal').classList.remove('active');
        productToDelete = null;
    }
    
    function showNotification(message, isError = false) {
        const notification = document.getElementById('successNotification');
        const messageElement = document.getElementById('notificationMessage');
        
        messageElement.textContent = message;
        notification.className = isError ? 'notification error show' : 'notification show';
        
        // Автоматически скрываем через 3 секунды
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }
    
    // Функция сортировки
    function sortTable(column) {
        const url = new URL(window.location.href);
        let order = 'ASC';
        
        if (url.searchParams.get('sort') === column) {
            order = url.searchParams.get('order') === 'ASC' ? 'DESC' : 'ASC';
        }
        
        url.searchParams.set('sort', column);
        url.searchParams.set('order', order);
        
        window.location.href = url.toString();
    }
    
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (productToDelete) {
            // Показываем индикатор загрузки
            document.getElementById('loadingOverlay').style.display = 'flex';
            
            // Отправляем AJAX-запрос
            fetch('manage_products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'delete_product=1&product_id=' + productToDelete
            })
            .then(response => response.json())
            .then(data => {
                // Скрываем индикатор загрузки
                document.getElementById('loadingOverlay').style.display = 'none';
                
                // Закрываем модальное окно
                closeModal();
                
                if (data.success) {
                    // Показываем уведомление об успехе
                    showNotification(data.message);
                    
                    // Обновляем страницу через 3 секунды
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {
                    // Показываем ошибку
                    showNotification(data.message, true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('loadingOverlay').style.display = 'none';
                showNotification('Произошла ошибка при удалении товара', true);
            });
        }
    });
    
    // Закрытие уведомления при клике
    document.getElementById('successNotification').addEventListener('click', function() {
        this.classList.remove('show');
    });
</script>
</body>
</html>