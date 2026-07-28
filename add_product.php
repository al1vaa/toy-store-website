<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 777) {
    header("Location: login.php");
    exit;
}

$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
$errors = [];
$success = false;

// Получаем данные для выпадающих списков
$categories = $db->query("SELECT * FROM Categories")->fetchAll(PDO::FETCH_ASSOC);
$manufacturers = $db->query("SELECT * FROM Manufacturers")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name']);
    $category_id = (int)$_POST['category_id'];
    $manufacturer_id = (int)$_POST['manufacturer_id'];
    $sku = trim($_POST['sku']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $discount = !empty($_POST['discount']) ? (float)$_POST['discount'] : 0;
    $material = trim($_POST['material']);
    $size = trim($_POST['size']);
    $color = trim($_POST['color']);
    $weight = !empty($_POST['weight']) ? (float)$_POST['weight'] : null;
    $features = trim($_POST['features']);
    
    // Валидация
    if (empty($product_name)) {
        $errors['product_name'] = 'Название товара обязательно';
    }
    
    if (empty($category_id)) {
        $errors['category_id'] = 'Категория обязательна';
    }
    
    if (empty($manufacturer_id)) {
        $errors['manufacturer_id'] = 'Производитель обязателен';
    }
    
    if (empty($sku)) {
        $errors['sku'] = 'Артикул обязателен';
    }
    
    if (empty($price) || $price <= 0) {
        $errors['price'] = 'Цена должна быть положительным числом';
    }
    
    if (empty($errors)) {
        try {
            $db->beginTransaction();
            
            // Создаем спецификации товара
            $stmt = $db->prepare("
                INSERT INTO ProductSpecifications 
                (material, size, color, weight, features) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$material, $size, $color, $weight, $features]);
            $specification_id = $db->lastInsertId();
            
            // Создаем галерею изображений (в реальном проекте нужно загружать файлы)
            $image_1 = 'product/default.jpg';
            $image_2 = null;
            $image_3 = null;
            
            $stmt = $db->prepare("
                INSERT INTO Galleries 
                (image_1, image_2, image_3) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$image_1, $image_2, $image_3]);
            $gallery_id = $db->lastInsertId();
            
            // Создаем сам товар
            $stmt = $db->prepare("
                INSERT INTO Products 
                (product_name, manufacturer_id, supplier_id, specification_id, 
                 category_id, gallery_id, sku, description, price, discount) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $product_name,
                $manufacturer_id,
                $manufacturer_id, // Используем manufacturer_id как supplier_id для простоты
                $specification_id,
                $category_id,
                $gallery_id,
                $sku,
                $description,
                $price,
                $discount
            ]);
            
            $db->commit();
            $success = true;
        } catch (PDOException $e) {
            $db->rollBack();
            $errors['database'] = 'Ошибка при добавлении товара: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить товар | BirchBark</title>
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
        
        /* Форма добавления товара */
        .add-form-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .dark-theme .add-form-container {
            background-color: #333;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .form-title {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.2rem;
            position: relative;
            padding-bottom: 15px;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
            border-radius: 3px;
        }
        
        .dark-theme .form-title {
            color: var(--secondary-color);
        }
        
        .form-group {
            margin-bottom: 25px;
            transition: var(--transition);
        }
        
        .form-group:hover {
            transform: translateX(5px);
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 16px;
        }
        
        .dark-theme .form-group label {
            color: var(--secondary-color);
        }
        
        .form-control {
            width: 100%;
            padding: 15px 5px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-family: inherit;
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
            height: 120px;
            resize: none;
        }
        
        .form-row {
            display: flex;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: var(--transition);
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 20px;
            box-shadow: var(--box-shadow);
        }
        
        .btn-submit:hover {
            background-color: #4a613d;
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(90, 114, 71, 0.3);
        }
        
        .success-message {
            background-color: rgba(46, 204, 113, 0.2);
            color: #27ae60;
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            text-align: center;
            border-left: 5px solid #27ae60;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .dark-theme .success-message {
            background-color: rgba(46, 204, 113, 0.3);
            color: #2ecc71;
            border-left-color: #2ecc71;
        }
        
        .error-message {
            background-color: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            border-left: 5px solid #e74c3c;
            animation: shake 0.5s ease-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        .dark-theme .error-message {
            background-color: rgba(231, 76, 60, 0.3);
        }
        
        /* Стили для секций характеристик */
        .spec-section {
            background-color: rgba(212, 167, 98, 0.1);
            padding: 25px;
            border-radius: var(--border-radius);
            margin: 40px 0;
            border-left: 4px solid var(--secondary-color);
            transition: var(--transition);
        }
        
        .spec-section:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .dark-theme .spec-section {
            background-color: rgba(212, 167, 98, 0.15);
        }
        
        /* Стили для файловых инпутов */
        .file-input-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-container input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background-color: #f9f9f9;
            border: 2px dashed #ddd;
            border-radius: var(--border-radius);
            text-align: center;
            transition: var(--transition);
        }
        
        .file-input-label:hover {
            background-color: #f0f0f0;
            border-color: var(--primary-color);
        }
        
        .file-input-label i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .dark-theme .file-input-label {
            background-color: #444;
            border-color: #555;
        }
        
        .dark-theme .file-input-label:hover {
            background-color: #555;
            border-color: var(--secondary-color);
        }
        
        /* Адаптивность */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .add-form-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    </header>

    <div class="container">
        <a href="catalog.php" class="back-btn"><i class="fas fa-arrow-left"></i> Назад в каталог</a>
        
        <div class="add-form-container">
            <h1 class="form-title">Добавить товар</h1>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> Товар успешно добавлен! <a href="catalog.php" style="color: inherit; text-decoration: underline;">Вернуться в каталог</a>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php foreach ($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="product_name">Название товара*</label>
                    <input type="text" id="product_name" name="product_name" class="form-control" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id">Категория*</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">Выберите категорию</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="manufacturer_id">Производитель*</label>
                        <select id="manufacturer_id" name="manufacturer_id" class="form-control" required>
                            <option value="">Выберите производителя</option>
                            <?php foreach ($manufacturers as $manufacturer): ?>
                                <option value="<?= $manufacturer['manufacturer_id'] ?>"><?= htmlspecialchars($manufacturer['manufacturer_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="sku">Артикул*</label>
                        <input type="text" id="sku" name="sku" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Цена*</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Скидка (%)</label>
                        <input type="number" id="discount" name="discount" class="form-control" step="0.01" min="0" max="100">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea id="description" name="description" class="form-control" placeholder="Введите подробное описание товара..."></textarea>
                </div>
                
                <div class="spec-section">
                    <h3 style="color: var(--primary-color); margin: 0 0 20px 0;">Характеристики товара</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="material">Материал</label>
                            <input type="text" id="material" name="material" class="form-control" placeholder="Например: Дерево, металл">
                        </div>
                        
                        <div class="form-group">
                            <label for="size">Размеры</label>
                            <input type="text" id="size" name="size" class="form-control" placeholder="Например: 120x80x40 см">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="color">Цвет</label>
                            <input type="text" id="color" name="color" class="form-control" placeholder="Например: Коричневый, белый">
                        </div>
                        
                        <div class="form-group">
                            <label for="weight">Вес (кг)</label>
                            <input type="number" id="weight" name="weight" class="form-control" step="0.01" min="0" placeholder="Например: 2.5">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="features">Особенности</label>
                        <textarea id="features" name="features" class="form-control" placeholder="Укажите уникальные особенности товара..."></textarea>
                    </div>
                </div>
                
                <div class="spec-section">
                    <h3 style="color: var(--primary-color); margin: 0 0 20px 0;">Изображения товара</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="image_1">Основное изображение*</label>
                            <div class="file-input-container">
                                <label class="file-input-label" for="image_1">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Выберите файл или перетащите сюда</span>
                                </label>
                                <input type="file" id="image_1" name="image_1" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="image_2">Дополнительное изображение</label>
                            <div class="file-input-container">
                                <label class="file-input-label" for="image_2">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Выберите файл или перетащите сюда</span>
                                </label>
                                <input type="file" id="image_2" name="image_2" class="form-control" accept="image/*">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="image_3">Дополнительное изображение</label>
                            <div class="file-input-container">
                                <label class="file-input-label" for="image_3">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Выберите файл или перетащите сюда</span>
                                </label>
                                <input type="file" id="image_3" name="image_3" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-plus-circle"></i> Добавить товар
                </button>
            </form>
        </div>
    </div>

    <!-- Футер -->
    <footer class="footer">
<?php include 'footer.php'; ?>
    </footer>

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
        
        // Показ имени выбранного файла
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const label = this.previousElementSibling;
                if (this.files.length > 0) {
                    label.querySelector('span').textContent = this.files[0].name;
                    label.style.borderColor = 'var(--primary-color)';
                    label.style.backgroundColor = 'rgba(90, 114, 71, 0.1)';
                } else {
                    label.querySelector('span').textContent = 'Выберите файл или перетащите сюда';
                    label.style.borderColor = '';
                    label.style.backgroundColor = '';
                }
            });
        });
    </script>
</body>
</html>