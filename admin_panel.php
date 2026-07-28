<?php
session_start();


// Проверка прав администратора (роль 777)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 777) {
    header("Location: login.php");
    exit();
}
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель | BirchBark</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Lora', serif;
        }
        
        body { 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            margin: 0; 
            background-color: var(--bg-light); 
        } 
        
        .dark-theme { 
            background-color: var(--bg-dark); 
            color: var(--text-dark); 
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            flex: 1 0 auto;
        }
        
        .admin-title {
            font-size: 32px;
            margin-bottom: 30px;
            color: var(--primary-color);
            font-weight: bold;
            text-align: center;
            font-family: 'Cormorant Garamond', serif;
        }
        
        .dark-theme .admin-title {
            color: var(--secondary-color);
        }
        
        .admin-sections {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .admin-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid rgba(90, 114, 71, 0.2);
        }
        
        body.dark-theme .admin-card {
            background: #333;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            border-color: rgba(212, 167, 98, 0.2);
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        
        .card-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 15px;
            font-family: 'Cormorant Garamond', serif;
        }
        
        .dark-theme .card-title {
            color: var(--secondary-color);
        }
        

        
        .dark-theme .card-icon {
            color: var(--secondary-color);
            background-color: rgba(212, 167, 98, 0.1);
        }
        
        .admin-actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .admin-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            font-size: 16px;
        }
        
        .admin-btn:hover {
            background-color: #4a613d;
            color: white;
            transform: translateX(5px);
        }
        
        .dark-theme .admin-btn {
            background-color: var(--secondary-color);
            color: #333;
        }
        
        .dark-theme .admin-btn:hover {
            background-color: #c49a5a;
            color: #333;
        }
        
        .admin-btn i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .admin-welcome {
            text-align: center;
            margin-bottom: 30px;
            font-size: 18px;
            color: #666;
        }
        
        .dark-theme .admin-welcome {
            color: #aaa;
        }
        
        @media (max-width: 768px) {
            .admin-sections {
                grid-template-columns: 1fr;
            }
            
            .admin-title {
                font-size: 28px;
            }
        }
        
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="admin-container">
        <h1 class="admin-title">Административная панель</h1>
        <p class="admin-welcome">Добро пожаловать, администратор! Здесь вы можете управлять содержимым сайта.</p>
        
        <div class="admin-sections">
            <!-- Управление товарами -->
            <div class="admin-card">
                <h2 class="card-title">
                    <span class="card-icon"><i class="fas fa-box-open"></i></span>
                    Управление товарами
                </h2>
                <div class="admin-actions">
                    <a href="add_product.php" class="admin-btn">
                        <i class="fas fa-plus"></i> Добавить товар
                    </a>
                    <a href="manage_products.php" class="admin-btn">
                        <i class="fas fa-edit"></i> Редактировать товары
                    </a>
                    <a href="catalog.php" class="admin-btn">
                        <i class="fas fa-eye"></i> Просмотр каталога
                    </a>
                </div>
            </div>
            
            <!-- Управление категориями -->
            <div class="admin-card">
                <h2 class="card-title">
                    <span class="card-icon"><i class="fas fa-tags"></i></span>
                    Управление категориями
                </h2>
                <div class="admin-actions">
                    <a href="add_category.php" class="admin-btn">
                        <i class="fas fa-plus"></i> Добавить категорию
                    </a>
                </div>
            </div>
            
            <!-- Управление производителями -->
            <div class="admin-card">
                <h2 class="card-title">
                    <span class="card-icon"><i class="fas fa-industry"></i></span>
                    Управление производителями
                </h2>
                <div class="admin-actions">
                    <a href="add_manufacturer.php" class="admin-btn">
                        <i class="fas fa-plus"></i> Добавить производителя
                    </a>
                </div>
            </div>
            
            <!-- Управление заказами -->
            <div class="admin-card">
                <h2 class="card-title">
                    <span class="card-icon"><i class="fas fa-shopping-bag"></i></span>
                    Управление заказами
                </h2>
                <div class="admin-actions">
                    <a href="manage_orders.php" class="admin-btn">
                        <i class="fas fa-list"></i> Менеджер заказов
                    </a>
                </div>
            </div>
            
            <!-- Управление пользователями -->
            <div class="admin-card">
                <h2 class="card-title">
                    <span class="card-icon"><i class="fas fa-users"></i></span>
                    Управление пользователями
                </h2>
                <div class="admin-actions">
                    <a href="admin_roles.php" class="admin-btn">
                        <i class="fas fa-list"></i> Менеджер заказов
                    </a>
                </div>
            </div>
            
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>