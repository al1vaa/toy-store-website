<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Подключение к БД
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
// Получение данных пользователя
$stmt = $db->prepare("
    SELECT u.*, r.role_name, uc.email, uc.username 
    FROM Users u
    JOIN UserCredentials uc ON u.user_id = uc.user_id
    JOIN Roles r ON u.role_id = r.role_id
    WHERE u.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль | BirchBark</title>
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
        
        /* Карточка профиля */
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .profile-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 40px;
            transition: var(--transition);
        }
        
        .dark-theme .profile-card {
            background-color: #333;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .profile-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .dark-theme .profile-header {
            border-bottom-color: rgba(255,255,255,0.1);
        }
        
        .profile-name {
            color: var(--primary-color);
            margin: 0;
            font-size: 28px;
        }
        
        .dark-theme .profile-name {
            color: var(--secondary-color);
        }
        
        .profile-role {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 10px;
            box-shadow: var(--box-shadow);
        }
        
        .profile-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .detail-item {
            padding: 20px;
            background-color: rgba(212, 167, 98, 0.1);
            border-radius: var(--border-radius);
            transition: var(--transition);
            border-left: 4px solid var(--secondary-color);
        }
        
        .detail-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow);
        }
        
        .dark-theme .detail-item {
            background-color: rgba(212, 167, 98, 0.15);
        }
        
        .detail-label {
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .dark-theme .detail-label {
            color: var(--secondary-color);
        }
        
        .detail-value {
            font-size: 16px;
        }
        
        .action-buttons {
            display: flex;
            gap: 20px;
            margin-top: 40px;
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
            text-decoration: none;
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
        
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-3px);
        }
        
        /* Адаптивность */
        @media (max-width: 768px) {
            .profile-details {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .profile-card {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="container">
        <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> На главную</a>
        
        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-header">
                    <h2 class="profile-name">
                        <?= htmlspecialchars($user['last_name'] . ' ' . $user['first_name'] . ($user['middle_name'] ? ' ' . $user['middle_name'] : '')) ?>
                    </h2>
                    <span class="profile-role"><?= htmlspecialchars($user['role_name']) ?></span>
                </div>
                
                <div class="profile-details">
                    <div class="detail-item">
                        <div class="detail-label"><i class="fas fa-user"></i> Имя пользователя</div>
                        <div class="detail-value"><?= htmlspecialchars($user['username']) ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label"><i class="fas fa-envelope"></i> Email</div>
                        <div class="detail-value"><?= htmlspecialchars($user['email']) ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label"><i class="fas fa-phone"></i> Телефон</div>
                        <div class="detail-value"><?= htmlspecialchars($user['phone']) ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label"><i class="fas fa-map-marker-alt"></i> Адрес</div>
                        <div class="detail-value"><?= htmlspecialchars($user['address']) ?></div>
                    </div>
                    
                    <?php if ($user['middle_name']): ?>
                    <div class="detail-item">
                        <div class="detail-label"><i class="fas fa-user-tag"></i> Отчество</div>
                        <div class="detail-value"><?= htmlspecialchars($user['middle_name']) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="action-buttons">
                    <a href="edit-profile.php" class="btn btn-primary"><i class="fas fa-edit"></i> Редактировать профиль</a>
                    <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Выйти</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Футер -->
    <footer class="footer">
<?php include 'footer.php'; ?>
    </footer>
</body>
</html>