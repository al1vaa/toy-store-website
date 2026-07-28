<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    $stmt = $db->prepare("
        SELECT p.*, m.manufacturer_name, c.category_name, ps.material, ps.size, 
               g.image_1, g.image_2, g.image_3
        FROM Products p
        JOIN Manufacturers m ON p.manufacturer_id = m.manufacturer_id
        JOIN Categories c ON p.category_id = c.category_id
        JOIN ProductSpecifications ps ON p.specification_id = ps.specification_id
        JOIN Galleries g ON p.gallery_id = g.gallery_id
        WHERE (p.product_name LIKE :query OR p.sku LIKE :query) AND p.is_active = 1
    ");
    $stmt->execute([':query' => "%$query%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты поиска | BirchBark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        /* Стили для продуктов */
        .products-section {
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
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            padding: 0 20px;
        }
        
        .product-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .dark-theme .product-card {
            background: #333;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .product-img {
            height: 200px;
            background-size: cover;
            background-position: center;
            background-color: #f5f5f5;
        }
        
        .dark-theme .product-img {
            background-color: #444;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-title {
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .product-category {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .dark-theme .product-category {
            color: #aaa;
        }
        
        .product-specs {
            font-size: 13px;
            color: #555;
            margin-bottom: 15px;
        }
        
        .dark-theme .product-specs {
            color: #bbb;
        }
        
        .product-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }
        
        .price {
            font-weight: bold;
            font-size: 20px;
            color: var(--primary-color);
        }
        
        .dark-theme .price {
            color: var(--secondary-color);
        }
        
        .add-to-cart {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .add-to-cart:hover {
            background-color: #4a613d;
        }
        
        .product-manufacturer {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }
        
        .dark-theme .product-manufacturer {
            color: #999;
        }
        
        /* Футер */
        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .footer h3 {
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 10px;
        }
        
        .footer a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
        }
        
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    </header>

    <div class="container">
        <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> На главную</a>
        
        <div class="search-results">
            <h1 class="section-title">Результаты поиска</h1>
            
            <?php if (!empty($query)): ?>
                <p class="search-query">Вы искали: "<?= htmlspecialchars($query) ?>"</p>
            <?php endif; ?>
            
            <?php if (!empty($results)): ?>
                <div class="product-grid">
                    <?php foreach ($results as $product): ?>
                        <div class="product-card" onclick="window.location='product.php?id=<?= $product['product_id'] ?>'">
                            <?php if (!empty($product['image_1'])): ?>
                                <div class="product-img" style="background-image: url('<?= htmlspecialchars($product['image_1']) ?>')"></div>
                            <?php else: ?>
                                <div class="product-img" style="background-image: url('https://via.placeholder.com/300x200?text=<?= urlencode($product['product_name']) ?>')"></div>
                            <?php endif; ?>
                            <div class="product-info">
                                <h3 class="product-title"><?= htmlspecialchars($product['product_name']) ?></h3>
                                <div class="product-category"><?= htmlspecialchars($product['category_name']) ?></div>
                                <div class="product-specs">
                                    <div><strong>Материал:</strong> <?= htmlspecialchars($product['material']) ?></div>
                                    <div><strong>Размер:</strong> <?= htmlspecialchars($product['size']) ?></div>
                                </div>
                                <div class="product-price">
                                    <span class="price"><?= number_format($product['price'], 2, '.', ' ') ?> ₽</span>
                                    <button class="add-to-cart">В корзину</button>
                                </div>
                                <div class="product-manufacturer">Производитель: <?= htmlspecialchars($product['manufacturer_name']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <p><i class="fas fa-search" style="font-size: 48px; margin-bottom: 20px;"></i></p>
                    <h3>Ничего не найдено</h3>
                    <p>Попробуйте изменить поисковый запрос</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Футер -->
    <footer class="footer">
<?php include 'footer.php'; ?>
    </footer>

    <script>
    </script>
</body>
</html>