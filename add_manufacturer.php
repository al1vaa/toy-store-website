<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 777) {
    header("Location: login.php");
    exit;
}

$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manufacturer_name = trim($_POST['manufacturer_name']);
    $country = trim($_POST['country']);
    $description = trim($_POST['description']);
    
    // Валидация
    if (empty($manufacturer_name)) {
        $errors['manufacturer_name'] = 'Название производителя обязательно';
    }
    
    if (empty($country)) {
        $errors['country'] = 'Страна обязательна';
    }
    
    if (empty($errors)) {
        try {
            $stmt = $db->prepare("
                INSERT INTO Manufacturers 
                (manufacturer_name, country, description) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$manufacturer_name, $country, $description]);
            
            $success = true;
        } catch (PDOException $e) {
            $errors['database'] = 'Ошибка при добавлении производителя: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить производителя | BirchBark</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .form-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .dark-theme .form-container {
            background: #333;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }
        
        .page-title {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .dark-theme .page-title {
            color: var(--secondary-color);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-color);
        }
        
        .dark-theme .form-group label {
            color: var(--secondary-color);
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 16px;
        }
        
        .dark-theme .form-control {
            background-color: #444;
            border-color: #555;
            color: white;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
            resize: none;

        }
        
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        .btn-submit:hover {
            background-color: #4a613d;
        }
        
        .success-message {
            background-color: rgba(46, 204, 113, 0.2);
            color: #27ae60;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .dark-theme .success-message {
            background-color: rgba(46, 204, 113, 0.3);
            color: #2ecc71;
        }
        
        .error-message {
            background-color: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .dark-theme .error-message {
            background-color: rgba(231, 76, 60, 0.3);
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
        
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
        <a href="catalog.php" class="back-btn"><i class="fas fa-arrow-left"></i> Назад в каталог</a>
    
    <div class="form-container">
        <h1 class="page-title">Добавить производителя</h1>
        
        <?php if ($success): ?>
            <div class="success-message">
                Производитель успешно добавлен! <a href="add_manufacturer.php">Добавить еще</a>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="manufacturer_name">Название производителя*</label>
                <input type="text" id="manufacturer_name" name="manufacturer_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="country">Страна*</label>
                <input type="text" id="country" name="country" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="description">Описание</label>
                <textarea id="description" name="description" class="form-control"></textarea>
            </div>
            
            <button type="submit" class="btn-submit">Добавить производителя</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>