<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']); // Может быть email или username
    $password = trim($_POST['password']);

    // Валидация
    if (empty($login)) {
        $errors['login'] = 'Введите email или имя пользователя';
    }

    if (empty($password)) {
        $errors['password'] = 'Введите пароль';
    }

    if (empty($errors)) {
        // Проверяем, является ли ввод email (содержит @)
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
        
        if ($isEmail) {
            $stmt = $db->prepare("SELECT * FROM UserCredentials WHERE email = ?");
        } else {
            $stmt = $db->prepare("SELECT * FROM UserCredentials WHERE username = ?");
        }
        
        $stmt->execute([$login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Получаем дополнительные данные пользователя из таблицы Users
            $stmt = $db->prepare("
                SELECT u.*, r.role_name 
                FROM Users u
                JOIN Roles r ON u.role_id = r.role_id
                WHERE u.user_id = ?
            ");
            $stmt->execute([$user['user_id']]);
            $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userInfo) {
                // Сохраняем данные в сессию
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $userInfo['first_name'];
                $_SESSION['last_name'] = $userInfo['last_name'];
                $_SESSION['middle_name'] = $userInfo['middle_name'];
                $_SESSION['phone'] = $userInfo['phone'];
                $_SESSION['address'] = $userInfo['address'];
                $_SESSION['role_id'] = $userInfo['role_id'];
                $_SESSION['role_name'] = $userInfo['role_name'];
                
                // Перенаправление после входа
                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] . '.php' : 'index.php';
                header("Location: $redirect");
                exit;
            } else {
                $errors['login'] = 'Ошибка загрузки профиля пользователя';
            }
        } else {
            $errors['login'] = 'Неверные учетные данные';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход | BirchBark</title>
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
            width: 100%;
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
                <h1 class="auth-title">Вход в аккаунт</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form class="auth-form" method="POST">
                    <div>
                        <label for="login">Email или имя пользователя</label>
                        <input type="text" id="login" name="login" value="<?= isset($_POST['login']) ? htmlspecialchars($_POST['login']) : '' ?>" required>
                    </div>
                    
                    <div class="password-container">
                        <label for="password">Пароль</label>
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-eye toggle-password" data-target="password"></i>
                    </div>
                    
                    <button type="submit">Войти</button>
                </form>
                
                <div class="auth-switch">
                    Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
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