<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка авторизации и прав администратора
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 777) {
    header("Location: login.php");
    exit;
}

$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
// Получаем ID продукта из URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Получаем информацию о продукте
$stmt = $db->prepare("
    SELECT p.*, ps.material, ps.size, ps.color, ps.weight, ps.features, 
           g.image_1, g.image_2, g.image_3, g.gallery_id,
           c.category_name, m.manufacturer_name, s.supplier_name
    FROM Products p
    JOIN ProductSpecifications ps ON p.specification_id = ps.specification_id
    JOIN Galleries g ON p.gallery_id = g.gallery_id
    JOIN Categories c ON p.category_id = c.category_id
    JOIN Manufacturers m ON p.manufacturer_id = m.manufacturer_id
    JOIN Suppliers s ON p.supplier_id = s.supplier_id
    WHERE p.product_id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Если продукт не найден - редирект
if (!$product) {
    header("Location: admin_products.php");
    exit;
}

// Получаем список категорий для выпадающего списка
$categories = $db->query("SELECT * FROM Categories ORDER BY category_name")->fetchAll(PDO::FETCH_ASSOC);

// Получаем список производителей
$manufacturers = $db->query("SELECT * FROM Manufacturers ORDER BY manufacturer_name")->fetchAll(PDO::FETCH_ASSOC);

// Получаем список поставщиков
$suppliers = $db->query("SELECT * FROM Suppliers ORDER BY supplier_name")->fetchAll(PDO::FETCH_ASSOC);

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db->beginTransaction();

        // Обновляем основную информацию о продукте
        $stmt = $db->prepare("
            UPDATE Products 
            SET product_name = ?, manufacturer_id = ?, supplier_id = ?, category_id = ?, 
                sku = ?, description = ?, price = ?, discount = ?, is_active = ?
            WHERE product_id = ?
        ");
        $stmt->execute([
            $_POST['product_name'],
            $_POST['manufacturer_id'],
            $_POST['supplier_id'],
            $_POST['category_id'],
            $_POST['sku'],
            $_POST['description'],
            $_POST['price'],
            $_POST['discount'],
            isset($_POST['is_active']) ? 1 : 0,
            $product_id
        ]);

        // Обновляем спецификации продукта
        $stmt = $db->prepare("
            UPDATE ProductSpecifications 
            SET material = ?, size = ?, color = ?, weight = ?, features = ?
            WHERE specification_id = ?
        ");
        $stmt->execute([
            $_POST['material'],
            $_POST['size'],
            $_POST['color'],
            $_POST['weight'],
            $_POST['features'],
            $product['specification_id']
        ]);

        // Обработка изображений
        $image_1 = $product['image_1'];
        $image_2 = $product['image_2'];
        $image_3 = $product['image_3'];

        // Функция для загрузки изображения
        function uploadImage($fieldName, $currentImage) {
            if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'product/';
                $ext = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
                $newFilename = uniqid() . '.' . $ext;
                $uploadPath = $uploadDir . $newFilename;
                
                if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadPath)) {
                    // Удаляем старое изображение, если оно существует
                    if ($currentImage && file_exists($currentImage)) {
                        unlink($currentImage);
                    }
                    return $uploadPath;
                }
            }
            return $currentImage;
        }

        // Обработка удаления изображений
        if (isset($_POST['remove_image_1']) && $product['image_1']) {
            if (file_exists($product['image_1'])) {
                unlink($product['image_1']);
            }
            $image_1 = null;
        }
        
        if (isset($_POST['remove_image_2']) && $product['image_2']) {
            if (file_exists($product['image_2'])) {
                unlink($product['image_2']);
            }
            $image_2 = null;
        }
        
        if (isset($_POST['remove_image_3']) && $product['image_3']) {
            if (file_exists($product['image_3'])) {
                unlink($product['image_3']);
            }
            $image_3 = null;
        }

        // Обновляем изображения
        $image_1 = uploadImage('image_1', $image_1);
        $image_2 = uploadImage('image_2', $image_2);
        $image_3 = uploadImage('image_3', $image_3);

        // Обновляем галерею
        $stmt = $db->prepare("
            UPDATE Galleries 
            SET image_1 = ?, image_2 = ?, image_3 = ?
            WHERE gallery_id = ?
        ");
        $stmt->execute([$image_1, $image_2, $image_3, $product['gallery_id']]);

        $db->commit();
        $_SESSION['notification'] = "Товар успешно обновлен";
        header("Location: edit_product.php?id=$product_id");
        exit;
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['notification'] = "Ошибка при обновлении товара: " . $e->getMessage();
        header("Location: edit_product.php?id=$product_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать <?= htmlspecialchars($product['product_name']) ?> | BirchBark</title>
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
            --border-radius: 14px;
            --transition: all 0.3s ease;
            --box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            --error-color: #e74c3c;
            --success-color: #2ecc71;
        }
        
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-light);
            color: var(--text-light);
            transition: var(--transition);
            line-height: 1.6;
        }
        
        h1, h2, h3 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
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
        
        /* Кнопка назад */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: var(--border-radius);
            margin: 30px 0;
            transition: var(--transition);
            font-size: 16px;
            box-shadow: var(--box-shadow);
        }
        
        .back-btn:hover {
            background-color: #4a613d;
            transform: translateY(-2px);
        }
        
        /* Форма редактирования */
        .edit-form {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 40px;
            margin-bottom: 40px;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .dark-theme .edit-form {
            background-color: #333;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .form-title {
            color: var(--primary-color);
            margin: 0 0 30px 0;
            font-size: 28px;
            position: relative;
            padding-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
            border-radius: 3px;
        }
        
        .dark-theme .form-title {
            color: var(--secondary-color);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .form-group {
            margin-bottom: 25px;
            transition: var(--transition);
        }
        
        .form-group:hover {
            transform: translateX(5px);
        }
        
        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 16px;
        }
        
        .dark-theme .form-label {
            color: var(--secondary-color);
        }
        
        .form-control {
            width: 90%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
            background-color: #f9f9f9;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 114, 71, 0.2);
            outline: none;
            background-color: white;
        }
        
        .dark-theme .form-control {
            background-color: #444;
            border-color: #555;
            color: white;
        }
        
        .dark-theme .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(212, 167, 98, 0.3);
            background-color: #555;
        }
        
        textarea.form-control {
            height: 150px;
            resize: none;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-check input {
            width: auto;
        }
        
        .image-preview {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .image-preview-item {
            width: 120px;
            height: 120px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            overflow: hidden;
            position: relative;
            transition: var(--transition);
        }
        
        .image-preview-item:hover {
            transform: scale(1.05);
        }
        
        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-preview-item .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(231, 76, 60, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .image-preview-item .remove-btn:hover {
            background: var(--error-color);
            transform: scale(1.1);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 15px 30px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--box-shadow);
        }
        
        .btn i {
            margin-right: 10px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #4a613d;
            transform: translateY(-3px);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-3px);
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .section-title {
            color: var(--primary-color);
            margin: 40px 0 20px;
            font-size: 22px;
            position: relative;
            padding-left: 15px;
        }
        
        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 70%;
            width: 4px;
            background: var(--secondary-color);
            border-radius: 2px;
        }
        
        .dark-theme .section-title {
            color: var(--secondary-color);
        }
        
        /* Уведомление */
        .notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: var(--success-color);
            color: white;
            padding: 15px 25px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--box-shadow);
            animation: slideIn 0.5s ease-out;
            z-index: 1000;
        }
        
        .custom-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: var(--error-color);
            color: white;
            padding: 15px 25px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--box-shadow);
            animation: slideIn 0.5s ease-out;
            z-index: 1000;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .notification.error {
            background-color: var(--error-color);
        }
        
        /* Адаптивность */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .edit-form {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    </header>

    <div class="container">
        <a href="manage_products.php" class="back-btn"><i class="fas fa-arrow-left"></i> Назад к списку товаров</a>
        
        <div class="edit-form">
            <h1 class="form-title"><i class="fas fa-edit"></i> Редактировать: <?= htmlspecialchars($product['product_name']) ?></h1>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <h3 class="section-title full-width">Основная информация</h3>
                    
                    <div class="form-group">
                        <label for="product_name">Название товара</label>
                        <input type="text" id="product_name" name="product_name" class="form-control" 
                               value="<?= htmlspecialchars($product['product_name']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sku">Артикул (SKU)</label>
                        <input type="text" id="sku" name="sku" class="form-control" 
                               value="<?= htmlspecialchars($product['sku']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Категория</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>" 
                                    <?= $category['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="manufacturer_id">Производитель</label>
                        <select id="manufacturer_id" name="manufacturer_id" class="form-control" required>
                            <?php foreach ($manufacturers as $manufacturer): ?>
                                <option value="<?= $manufacturer['manufacturer_id'] ?>" 
                                    <?= $manufacturer['manufacturer_id'] == $product['manufacturer_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($manufacturer['manufacturer_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="supplier_id">Поставщик</label>
                        <select id="supplier_id" name="supplier_id" class="form-control" required>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier['supplier_id'] ?>" 
                                    <?= $supplier['supplier_id'] == $product['supplier_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($supplier['supplier_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Цена (₽)</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" 
                               value="<?= htmlspecialchars($product['price']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Скидка (%)</label>
                        <input type="number" id="discount" name="discount" class="form-control" step="0.01" min="0" max="100" 
                               value="<?= htmlspecialchars($product['discount']) ?>">
                    </div>
                    
                    <div class="form-group form-check">
                        <input type="checkbox" id="is_active" name="is_active" class="form-control" 
                               <?= $product['is_active'] ? 'checked' : '' ?>>
                        <label for="is_active">Активный товар</label>
                    </div>
                    
                    <h3 class="section-title full-width">Характеристики</h3>
                    
                    <div class="form-group">
                        <label for="material">Материал</label>
                        <input type="text" id="material" name="material" class="form-control" 
                               value="<?= htmlspecialchars($product['material']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="size">Размеры</label>
                        <input type="text" id="size" name="size" class="form-control" 
                               value="<?= htmlspecialchars($product['size']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="color">Цвет</label>
                        <input type="text" id="color" name="color" class="form-control" 
                               value="<?= htmlspecialchars($product['color']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="weight">Вес (кг)</label>
                        <input type="number" id="weight" name="weight" class="form-control" step="0.01" min="0" 
                               value="<?= htmlspecialchars($product['weight']) ?>">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="features">Особенности</label>
                        <textarea id="features" name="features" class="form-control"><?= htmlspecialchars($product['features']) ?></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description">Описание</label>
                        <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
                    </div>
                    
                    <h3 class="section-title full-width">Изображения</h3>
                    
                    <div class="form-group full-width">
                        <label>Текущие изображения</label>
                        
                        <div class="image-preview">
                            <div class="image-preview-item">
                                <?php if ($product['image_1']): ?>
                                    <img src="<?= htmlspecialchars($product['image_1']) ?>" alt="Основное изображение">
                                    <button type="button" class="remove-btn" onclick="confirmImageRemoval(1)"><i class="fas fa-times"></i></button>
                                    <input type="hidden" name="remove_image_1" id="remove_image_1" value="0">
                                <?php else: ?>
                                    <div style="background: #eee; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image" style="font-size: 24px; color: #999;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="image-preview-item">
                                <?php if ($product['image_2']): ?>
                                    <img src="<?= htmlspecialchars($product['image_2']) ?>" alt="Дополнительное изображение 1">
                                    <button type="button" class="remove-btn" onclick="confirmImageRemoval(2)"><i class="fas fa-times"></i></button>
                                    <input type="hidden" name="remove_image_2" id="remove_image_2" value="0">
                                <?php else: ?>
                                    <div style="background: #eee; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image" style="font-size: 24px; color: #999;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="image-preview-item">
                                <?php if ($product['image_3']): ?>
                                    <img src="<?= htmlspecialchars($product['image_3']) ?>" alt="Дополнительное изображение 2">
                                    <button type="button" class="remove-btn" onclick="confirmImageRemoval(3)"><i class="fas fa-times"></i></button>
                                    <input type="hidden" name="remove_image_3" id="remove_image_3" value="0">
                                <?php else: ?>
                                    <div style="background: #eee; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image" style="font-size: 24px; color: #999;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="image_1">Основное изображение</label>
                        <input type="file" id="image_1" name="image_1" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="image_2">Дополнительное изображение 1</label>
                        <input type="file" id="image_2" name="image_2" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="image_3">Дополнительное изображение 2</label>
                        <input type="file" id="image_3" name="image_3" class="form-control">
                    </div>
                    
                    <div class="form-group full-width" style="display: flex; justify-content: flex-end; gap: 15px;">
                        <button type="reset" class="btn btn-secondary"><i class="fas fa-times"></i> Отмена</button>
                        <button type="submit" name="update_product" class="btn btn-primary"><i class="fas fa-save"></i> Сохранить изменения</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Футер -->
    <footer class="footer">
<?php include 'footer.php'; ?>
    </footer>
    
    <?php if (isset($_SESSION['notification'])): ?>
        <div id="notification" class="notification <?= isset($_SESSION['error']) ? 'error' : '' ?>">
            <i class="fas <?= isset($_SESSION['error']) ? 'fa-exclamation-circle' : 'fa-check-circle' ?>"></i>
            <span id="notification-message"><?= $_SESSION['notification'] ?></span>
        </div>
        <?php 
            unset($_SESSION['notification']);
            if (isset($_SESSION['error'])) unset($_SESSION['error']);
        ?>
    <?php endif; ?>

    <script>
        // Анимация при фокусе
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateX(8px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateX(0)';
            });
        });
        
        // Удаление уведомления через 5 секунд
        const notification = document.getElementById('notification');
        if (notification) {
            setTimeout(() => {
                notification.style.animation = 'slideIn 0.5s ease-out reverse';
                setTimeout(() => notification.remove(), 500);
            }, 5000);
        }
        
        // Подтверждение удаления изображения
        function confirmImageRemoval(imageNum) {
            if (confirm('Вы уверены, что хотите удалить это изображение?')) {
                document.getElementById('remove_image_' + imageNum).value = '1';
                const imgContainer = document.querySelector(`.image-preview-item:nth-child(${imageNum})`);
                imgContainer.innerHTML = `
                    <div style="background: #eee; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image" style="font-size: 24px; color: #999;"></i>
                    </div>
                `;
                
                // Показываем уведомление
                showNotification('Изображение будет удалено после сохранения изменений', false);
            }
        }
        
        // Функция для показа уведомления
        function showNotification(message, isSuccess = true) {
            // Сначала удаляем предыдущее уведомление, если оно есть
            const existingNotification = document.querySelector('.custom-notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            const notification = document.createElement('div');
            notification.className = `custom-notification ${isSuccess ? '' : 'error'}`;
            notification.innerHTML = `
                <i class="fas ${isSuccess ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(notification);
            
            // Добавляем стили для кастомного уведомления
            notification.style.position = 'fixed';
            notification.style.bottom = '30px';
            notification.style.right = '30px';
            notification.style.backgroundColor = isSuccess ? 'var(--success-color)' : 'var(--error-color)';
            notification.style.color = 'white';
            notification.style.padding = '15px 25px';
            notification.style.borderRadius = 'var(--border-radius)';
            notification.style.display = 'flex';
            notification.style.alignItems = 'center';
            notification.style.gap = '10px';
            notification.style.boxShadow = 'var(--box-shadow)';
            notification.style.animation = 'slideIn 0.5s ease-out';
            notification.style.zIndex = '1000';
            
            // Удаление через 5 секунд
            setTimeout(() => {
                notification.style.animation = 'slideIn 0.5s ease-out reverse';
                setTimeout(() => notification.remove(), 500);
            }, 5000);
        }
    </script>
</body>
</html>