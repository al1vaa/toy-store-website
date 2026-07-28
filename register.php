<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $last_name = trim($_POST['last_name']);
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Валидация
    if (empty($username)) {
        $errors['username'] = 'Имя пользователя обязательно';
    } else {
        // Проверка уникальности username
        $stmt = $db->prepare("SELECT * FROM UserCredentials WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'Это имя пользователя уже занято';
        }
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email';
    } else {
        // Проверка уникальности email
        $stmt = $db->prepare("SELECT * FROM UserCredentials WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors['email'] = 'Этот email уже зарегистрирован';
        }
    }

    if (empty($password)) {
        $errors['password'] = 'Введите пароль';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Пароль должен быть не менее 8 символов';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors['password'] = 'Пароль должен содержать хотя бы одну заглавную букву';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Пароли не совпадают';
    }

    if (empty($last_name)) {
        $errors['last_name'] = 'Фамилия обязательна';
    }

    if (empty($first_name)) {
        $errors['first_name'] = 'Имя обязательно';
    }

    if (empty($phone)) {
        $errors['phone'] = 'Телефон обязателен';
    }

    if (empty($address)) {
        $errors['address'] = 'Адрес обязателен';
    }

    // Регистрация
    if (empty($errors)) {
        try {
            $db->beginTransaction();

            // Определяем роль пользователя
            $role_id = 1111; // Обычный пользователь по умолчанию
            
            // Если username начинается с "bADMIN_", назначаем роль 777 (админ)
            if (strpos($username, 'bADMIN_') === 0) {
                $role_id = 777;
            }

            // Хеширование пароля
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Создаем учетные данные
            $stmt = $db->prepare("INSERT INTO UserCredentials (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $email]);
            $user_id = $db->lastInsertId();

            // Создаем профиль пользователя
            $stmt = $db->prepare("INSERT INTO Users (user_id, last_name, first_name, middle_name, phone, address, role_id) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $last_name, $first_name, $middle_name, $phone, $address, $role_id]);

            $db->commit();

            // Автоматический вход после регистрации
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['middle_name'] = $middle_name;
            $_SESSION['phone'] = $phone;
            $_SESSION['address'] = $address;
            $_SESSION['role_id'] = $role_id;

            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $db->rollBack();
            $errors['database'] = 'Ошибка при регистрации: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация | BirchBark</title>
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
        
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 14px;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }
        
        .back-btn:hover {
            background-color: #4a613d;
        }
        
        .auth-page {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 120px);
            padding: 20px;
        }
        
        .auth-container {
            background-color: white;
            border-radius: 18px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            padding: 30px;
            margin: 50px auto;
        }
        
        .dark-theme .auth-container {
            background-color: #333;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }
        
        .auth-title {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .dark-theme .auth-title {
            color: var(--secondary-color);
        }
        
        .auth-form label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-light);
        }
        
        .dark-theme .auth-form label {
            color: var(--text-dark);
        }
        
        .auth-form input {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 14px;
        }
        
        .dark-theme .auth-form input {
            background-color: #444;
            border-color: #555;
            color: white;
        }
        
        .auth-form button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 14px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
        }
        
        .auth-form button:hover {
            background-color: #4a613d;
        }
        
        .auth-switch {
            text-align: center;
            margin-top: 20px;
        }
        
        .auth-switch a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .dark-theme .auth-switch a {
            color: var(--secondary-color);
        }
        
        .error {
            color: #e74c3c;
            margin-bottom: 15px;
            padding: 10px;
            background-color: rgba(231, 76, 60, 0.1);
            border-radius: 14px;
        }
        
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 35px;
            cursor: pointer;
            color: var(--text-light);
        }
        
        .dark-theme .toggle-password {
            color: var(--text-dark);
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <div class="auth-page">
        <div class="container">
            <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> На главную</a>
            
            <div class="auth-container">
                <h1 class="auth-title">Регистрация</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form class="auth-form" method="POST">
                    <div>
                        <label for="username">Имя пользователя</label>
                        <input type="text" id="username" name="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
                    </div>
                    
                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                    </div>
                    
                    <div class="password-container">
                        <label for="password">Пароль (мин. 8 символов, 1 заглавная буква)</label>
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-eye toggle-password" data-target="password"></i>
                    </div>
                    
                    <div class="password-container">
                        <label for="confirm_password">Подтвердите пароль</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <i class="fas fa-eye toggle-password" data-target="confirm_password"></i>
                    </div>
                    
                    <div>
                        <label for="last_name">Фамилия</label>
                        <input type="text" id="last_name" name="last_name" value="<?= isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '' ?>" required>
                    </div>
                    
                    <div>
                        <label for="first_name">Имя</label>
                        <input type="text" id="first_name" name="first_name" value="<?= isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '' ?>" required>
                    </div>
                    
                    <div>
                        <label for="middle_name">Отчество</label>
                        <input type="text" id="middle_name" name="middle_name" value="<?= isset($_POST['middle_name']) ? htmlspecialchars($_POST['middle_name']) : '' ?>">
                    </div>
                    
                    <div>
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" name="phone" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" required>
                    </div>
                    
                    <div>
                        <label for="address">Адрес</label>
                        <input type="text" id="address" name="address" value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>" required>
                    </div>
                    
                    <button type="submit">Зарегистрироваться</button>
                </form>
                
                <div class="auth-switch">
                    Уже есть аккаунт? <a href="login.php">Войти</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Футер -->
    <footer class="footer">
<?php include 'footer.php'; ?>
    </footer>
    <script>
        // Функция для переключения видимости пароля
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    </script>
</body>
</html>