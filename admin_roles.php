<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка авторизации и прав администратора
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role_id'] != 777) {
    header("Location: index.php");
    exit;
}

// Подключение к базе данных
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');

// Обработка изменения роли
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_role') {
    $user_id = (int)$_POST['user_id'];
    $new_role = (int)$_POST['new_role'];
    
    try {
        $stmt = $db->prepare("UPDATE Users SET role_id = ? WHERE user_id = ?");
        $stmt->execute([$new_role, $user_id]);
        
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Роль пользователя успешно изменена'
        ];
        
        header("Location: admin_roles.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Ошибка при изменении роли: ' . $e->getMessage()
        ];
    }
}

// Получение списка пользователей
$usersQuery = $db->query("
    SELECT u.user_id, u.last_name, u.first_name, u.middle_name, u.phone, uc.email, r.role_name, r.role_id 
    FROM Users u
    JOIN UserCredentials uc ON u.user_id = uc.user_id
    JOIN Roles r ON u.role_id = r.role_id
    ORDER BY u.last_name, u.first_name
");
$users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

// Получение списка ролей
$rolesQuery = $db->query("SELECT * FROM Roles ORDER BY role_id");
$roles = $rolesQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BirchBark - Управление ролями</title>
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
        
        /* Основные стили контейнера */
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Стили для заголовка */
        .section-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: var(--primary-color);
        }
        
        .dark-theme .section-title {
            color: var(--secondary-color);
        }
        
        /* Стили для таблицы пользователей */
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .dark-theme .users-table {
            background: #333;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }
        
        .users-table th, 
        .users-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .dark-theme .users-table th,
        .dark-theme .users-table td {
            border-bottom-color: #555;
        }
        
        .users-table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
        }
        
        .users-table tr:hover {
            background-color: rgba(90, 114, 71, 0.1);
        }
        
        .dark-theme .users-table tr:hover {
            background-color: rgba(212, 167, 98, 0.1);
        }
        
        /* Стили для формы изменения роли */
        .role-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .role-select {
            padding: 8px 12px;
            border-radius: 14px;
            border: 1px solid #ddd;
            background-color: white;
            font-family: inherit;
        }
        
        .dark-theme .role-select {
            background-color: #444;
            border-color: #555;
            color: white;
        }
        
        .change-role-btn {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: inherit;
        }
        
        .change-role-btn:hover {
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
    
    <div class="admin-container">
        <h2 class="section-title">Управление ролями пользователей</h2>
        
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Текущая роль</th>
                    <th>Изменить роль</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['user_id'] ?></td>
                        <td><?= htmlspecialchars($user['last_name'] . ' ' . $user['first_name'] . ($user['middle_name'] ? ' ' . $user['middle_name'] : '')) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= htmlspecialchars($user['role_name']) ?></td>
                        <td>
                            <form class="role-form" method="post" onsubmit="return confirmRoleChange(this, <?= $user['user_id'] ?>, '<?= htmlspecialchars(addslashes($user['first_name'] . ' ' . $user['last_name'])) ?>')">
                                <input type="hidden" name="action" value="change_role">
                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                
                                <select name="new_role" class="role-select" required>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['role_id'] ?>" <?= $role['role_id'] == $user['role_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($role['role_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <button type="submit" class="change-role-btn">
                                    <i class="fas fa-sync-alt"></i> Изменить
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
        // Элементы интерфейса
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        const confirmationModal = document.getElementById('confirmation-modal');
        const confirmationMessage = document.getElementById('confirmation-message');
        const confirmActionBtn = document.getElementById('confirm-action');
        const cancelConfirmationBtn = document.getElementById('cancel-confirmation');
        
        let pendingForm = null;
        
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
        function showConfirmation(message, form) {
            confirmationMessage.textContent = message;
            pendingForm = form;
            confirmationModal.classList.add('active');
        }
        
        // Закрыть подтверждение
        function closeConfirmation() {
            confirmationModal.classList.remove('active');
            pendingForm = null;
        }
        
        // Подтверждение изменения роли
        function confirmRoleChange(form, userId, userName) {
            const roleSelect = form.querySelector('select[name="new_role"]');
            const newRole = roleSelect.options[roleSelect.selectedIndex].text;
            
            showConfirmation(
                `Вы уверены, что хотите изменить роль пользователя ${userName} (ID: ${userId}) на "${newRole}"?`, 
                form
            );
            
            return false; // Предотвращаем стандартную отправку формы
        }
        
        // Обработчики для модального окна подтверждения
        confirmActionBtn.addEventListener('click', () => {
            if (pendingForm) {
                pendingForm.submit();
            }
            closeConfirmation();
        });
        
        cancelConfirmationBtn.addEventListener('click', closeConfirmation);
        
        confirmationModal.addEventListener('click', (e) => {
            if (e.target === confirmationModal) {
                closeConfirmation();
            }
        });
        
        // Показать уведомление из PHP, если оно есть
        <?php if (isset($_SESSION['notification'])): ?>
            showNotification('<?= addslashes($_SESSION['notification']['message']) ?>', '<?= $_SESSION['notification']['type'] ?>');
            <?php unset($_SESSION['notification']); ?>
        <?php endif; ?>
    </script>
</body>
</html>