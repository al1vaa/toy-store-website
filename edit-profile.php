<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Подключение к БД
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
// Получение текущих данных пользователя
$stmt = $db->prepare("
    SELECT u.*, uc.email, uc.username 
    FROM Users u
    JOIN UserCredentials uc ON u.user_id = uc.user_id
    WHERE u.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = false;

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_name = trim($_POST['middle_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    
    // Если отправлена форма смены пароля
    if (isset($_POST['change_password'])) {
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);
        
        // Проверка пароля
        $password_changed = false;
        if (!empty($new_password)) {
            if (empty($current_password)) {
                $errors['current_password'] = 'Введите текущий пароль';
            } else {
                // Проверка текущего пароля
                $stmt = $db->prepare("SELECT password FROM UserCredentials WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $db_password = $stmt->fetchColumn();
                
                if (!password_verify($current_password, $db_password)) {
                    $errors['current_password'] = 'Неверный текущий пароль';
                } elseif (strlen($new_password) < 6) {
                    $errors['new_password'] = 'Пароль должен содержать минимум 6 символов';
                } elseif ($new_password !== $confirm_password) {
                    $errors['confirm_password'] = 'Пароли не совпадают';
                } else {
                    $password_changed = true;
                }
            }
        }
    }

    // Валидация основных полей
    if (empty($first_name)) $errors['first_name'] = 'Введите имя';
    if (empty($last_name)) $errors['last_name'] = 'Введите фамилию';
    if (empty($phone)) $errors['phone'] = 'Введите телефон';
    if (empty($address)) $errors['address'] = 'Введите адрес';
    
    if (empty($email)) {
        $errors['email'] = 'Введите email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    } else {
        // Проверка уникальности email
        $stmt = $db->prepare("SELECT user_id FROM UserCredentials WHERE email = ? AND user_id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        if ($stmt->rowCount() > 0) {
            $errors['email'] = 'Этот email уже используется';
        }
    }

    // Проверка уникальности username
    if (empty($username)) {
        $errors['username'] = 'Введите имя пользователя';
    } else {
        $stmt = $db->prepare("SELECT user_id FROM UserCredentials WHERE username = ? AND user_id != ?");
        $stmt->execute([$username, $_SESSION['user_id']]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'Это имя пользователя уже занято';
        }
    }

    // Если нет ошибок - обновляем данные
    if (empty($errors)) {
        try {
            $db->beginTransaction();
            
            // Обновляем UserCredentials
            $update_fields = "email = ?, username = ?";
            $update_params = [$email, $username];
            
            if (isset($password_changed) && $password_changed) {
                $update_fields .= ", password = ?";
                $update_params[] = password_hash($new_password, PASSWORD_DEFAULT);
            }
            
            $stmt = $db->prepare("UPDATE UserCredentials SET $update_fields WHERE user_id = ?");
            $update_params[] = $_SESSION['user_id'];
            $stmt->execute($update_params);
            
            // Обновляем Users
            $stmt = $db->prepare("
                UPDATE Users SET 
                first_name = ?, 
                last_name = ?, 
                middle_name = ?, 
                phone = ?, 
                address = ?
                WHERE user_id = ?
            ");
            $stmt->execute([
                $first_name, 
                $last_name, 
                $middle_name, 
                $phone, 
                $address,
                $_SESSION['user_id']
            ]);
            
            $db->commit();
            
            // Обновляем данные в сессии
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            
            $success_message = "Профиль успешно обновлен";
            if (isset($password_changed) && $password_changed) {
                $success_message .= " (пароль изменен)";
            }
            
            $_SESSION['notification'] = $success_message;
            $_SESSION['success'] = true;
            header("Location: edit-profile.php");
            exit;
            
        } catch (Exception $e) {
            $db->rollBack();
            $errors['database'] = 'Ошибка при обновлении данных: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование профиля | BirchBark</title>
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
        .edit-container {
            max-width: 800px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .edit-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 40px;
            transition: var(--transition);
        }
        
        .dark-theme .edit-card {
            background-color: #333;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .edit-title {
            color: var(--primary-color);
            margin: 0 0 30px 0;
            font-size: 28px;
            position: relative;
            padding-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .edit-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
            border-radius: 3px;
        }
        
        .dark-theme .edit-title {
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
        
        .form-input {
            width: 90%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
            background-color: #f9f9f9;
        }
        
        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 114, 71, 0.2);
            outline: none;
            background-color: white;
        }
        
        .dark-theme .form-input {
            background-color: #444;
            border-color: #555;
            color: white;
        }
        
        .dark-theme .form-input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(212, 167, 98, 0.3);
            background-color: #555;
        }
        
        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error-color);
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            border-left: 4px solid var(--error-color);
            animation: shake 0.5s ease-out;
        }
        
        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            border-left: 4px solid var(--success-color);
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .dark-theme .alert-error {
            background-color: rgba(231, 76, 60, 0.2);
        }
        
        .dark-theme .alert-success {
            background-color: rgba(46, 204, 113, 0.2);
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
        
        .password-toggle {
            position: absolute;
            right: 30px;  /* Уменьшил значение для смещения влево */
            bottom: 10px;  /* Изменил top на bottom для смещения вниз */
            transform: none;  /* Убрал transform, так как он больше не нужен */
            cursor: pointer;
            color: #777;
            font-size: 18px;  /* Можно увеличить размер иконки */
        }
        
        .password-container {
            position: relative;
            padding-bottom: -90px;
        }
        
        /* Модальное окно */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            animation: fadeIn 0.3s;
        }
        
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 80%;
            max-width: 500px;
            animation: slideDown 0.3s;
        }
        
        .dark-theme .modal-content {
            background-color: #333;
        }
        
        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close-modal:hover {
            color: var(--error-color);
        }
        
        .change-password-link {
            color: var(--primary-color);
            text-decoration: underline;
            cursor: pointer;
            margin-top: 20px;
            display: inline-block;
        }
        
        .dark-theme .change-password-link {
            color: var(--secondary-color);
        }
        
        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Адаптивность */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .edit-card {
                padding: 25px;
            }
            
            .modal-content {
                width: 90%;
                margin: 20% auto;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    </header>

    <div class="container">
        <a href="profile.php" class="back-btn"><i class="fas fa-arrow-left"></i> Назад к профилю</a>
        
        <div class="edit-container">
            <div class="edit-card">
                <h1 class="edit-title"><i class="fas fa-user-edit"></i> Редактирование профиля</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert-error">
                        <p><strong><i class="fas fa-exclamation-circle"></i> Ошибка!</strong> Пожалуйста, исправьте следующие ошибки:</p>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['notification'])): ?>
                    <div class="alert-success">
                        <p><strong><i class="fas fa-check-circle"></i> Успешно!</strong> <?= $_SESSION['notification'] ?></p>
                    </div>
                    <?php 
                        unset($_SESSION['notification']);
                        unset($_SESSION['success']);
                    ?>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="username" class="form-label">Имя пользователя</label>
                            <input type="text" id="username" name="username" class="form-input" 
                                   value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-input" 
                                   value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name" class="form-label">Фамилия</label>
                            <input type="text" id="last_name" name="last_name" class="form-input" 
                                   value="<?= htmlspecialchars($user['last_name']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="first_name" class="form-label">Имя</label>
                            <input type="text" id="first_name" name="first_name" class="form-input" 
                                   value="<?= htmlspecialchars($user['first_name']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="middle_name" class="form-label">Отчество</label>
                            <input type="text" id="middle_name" name="middle_name" class="form-input" 
                                   value="<?= htmlspecialchars($user['middle_name']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="tel" id="phone" name="phone" class="form-input" 
                                   value="<?= htmlspecialchars($user['phone']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">Адрес</label>
                            <input type="text" id="address" name="address" class="form-input" 
                                   value="<?= htmlspecialchars($user['address']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Сохранить изменения</button>
                        <a href="profile.php" class="btn btn-secondary"><i class="fas fa-times"></i> Отмена</a>
                    </div>
                    
                    <a class="change-password-link" onclick="openPasswordModal()">
                        <i class="fas fa-key"></i> Изменить пароль
                    </a>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Модальное окно для смены пароля -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closePasswordModal()">&times;</span>
            <h3><i class="fas fa-key"></i> Смена пароля</h3>
            
            <form method="POST" id="passwordForm">
                <div class="form-group password-container">
                    <label for="current_password" class="form-label">Текущий пароль</label>
                    <input type="password" id="current_password" name="current_password" class="form-input">
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('current_password')"></i>
                </div>
                
                <div class="form-group password-container">
                    <label for="new_password" class="form-label">Новый пароль</label>
                    <input type="password" id="new_password" name="new_password" class="form-input">
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('new_password')"></i>
                </div>
                
                <div class="form-group password-container">
                    <label for="confirm_password" class="form-label">Подтвердите новый пароль</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input">
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm_password')"></i>
                </div>
                
                <input type="hidden" name="change_password" value="1">
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Сохранить пароль</button>
                    <button type="button" class="btn btn-secondary" onclick="closePasswordModal()"><i class="fas fa-times"></i> Отмена</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Футер -->
    <footer class="footer">
<?php include 'footer.php'; ?>
    </footer>

    <script>
        // Анимация при фокусе
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateX(8px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateX(0)';
            });
        });
        
        // Переключение видимости пароля
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Автоматическое скрытие уведомления через 5 секунд
        const notification = document.querySelector('.alert-success');
        if (notification) {
            setTimeout(() => {
                notification.style.animation = 'slideIn 0.5s ease-out reverse';
                setTimeout(() => notification.remove(), 500);
            }, 5000);
        }
        
        // Управление модальным окном
        const passwordModal = document.getElementById('passwordModal');
        
        function openPasswordModal() {
            passwordModal.style.display = 'block';
        }
        
        function closePasswordModal() {
            passwordModal.style.display = 'none';
            // Очищаем поля при закрытии
            document.getElementById('current_password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('confirm_password').value = '';
        }
        
        // Закрытие модального окна при клике вне его
        window.onclick = function(event) {
            if (event.target == passwordModal) {
                closePasswordModal();
            }
        }
        
        // Обработка отправки формы пароля
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            // Можно добавить дополнительную валидацию здесь
            closePasswordModal();
        });
    </script>
</body>
</html>