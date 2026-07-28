<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Подключение к БД
$db = new PDO('mysql:host=localhost;dbname=supercy8_bd;charset=utf8', 'supercy8_bd', 'al1vaawitch133711072005!');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Получение входных данных
$input = json_decode(file_get_contents('php://input'), true);
if ($input === null && !empty($_POST)) {
    $input = $_POST;
}

// Обработка запросов
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($input['action'])) {
            sendJsonResponse(false, 'Не указано действие');
        }

        switch ($input['action']) {
            case 'add':
                handleAddReview($db, $input);
                break;
            case 'update':
                handleUpdateReview($db, $input);
                break;
            case 'delete':
                handleDeleteReview($db, $input);
                break;
            default:
                sendJsonResponse(false, 'Неизвестное действие');
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!isset($_GET['action'])) {
            sendJsonResponse(false, 'Не указано действие');
        }

        switch ($_GET['action']) {
            case 'get':
                handleGetReviews($db, $_GET);
                break;
            case 'count':
                handleGetReviewsCount($db, $_GET);
                break;
            default:
                sendJsonResponse(false, 'Неизвестное действие');
        }
    } else {
        sendJsonResponse(false, 'Неподдерживаемый метод запроса');
    }
} catch (PDOException $e) {
    sendJsonResponse(false, 'Ошибка базы данных: ' . $e->getMessage());
} catch (Exception $e) {
    sendJsonResponse(false, 'Ошибка: ' . $e->getMessage());
}

// Функции обработки действий

function handleAddReview($db, $data) {
    // Проверка авторизации
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Необходимо авторизоваться');
    }

    // Проверка данных
    if (!isset($data['product_id']) || !isset($data['rating']) || !isset($data['review_text'])) {
        throw new Exception('Неверные данные');
    }

    $product_id = (int)$data['product_id'];
    $rating = (int)$data['rating'];
    $review_text = trim($data['review_text']);

    // Валидация
    if ($rating < 1 || $rating > 5) {
        throw new Exception('Некорректная оценка');
    }

    if (empty($review_text)) {
        throw new Exception('Текст отзыва не может быть пустым');
    }

    // Проверяем, есть ли уже отзыв от этого пользователя на этот товар
    $checkQuery = $db->prepare("SELECT * FROM Reviews WHERE product_id = ? AND user_id = ?");
    $checkQuery->execute([$product_id, $_SESSION['user_id']]);
    $existingReview = $checkQuery->fetch();

    if ($existingReview) {
        throw new Exception('Вы уже оставляли отзыв на этот товар');
    }

    // Проверяем, покупал ли пользователь этот товар и был ли он доставлен
    $checkPurchase = $db->prepare("
        SELECT oi.order_item_id 
        FROM OrderItems oi
        JOIN Orders o ON oi.order_id = o.order_id
        WHERE o.user_id = ? AND oi.product_id = ? AND o.status = 'delivered'
        LIMIT 1
    ");
    $checkPurchase->execute([$_SESSION['user_id'], $product_id]);
    
    if (!$checkPurchase->fetch()) {
        throw new Exception('Вы можете оставить отзыв только на доставленные товары');
    }

    // Добавляем отзыв
    $insertQuery = $db->prepare("
        INSERT INTO Reviews (product_id, user_id, rating, review_text)
        VALUES (?, ?, ?, ?)
    ");
    $insertQuery->execute([$product_id, $_SESSION['user_id'], $rating, $review_text]);
    
    // Обновляем средний рейтинг товара
    updateProductRating($db, $product_id);
    
    sendJsonResponse(true, 'Отзыв успешно добавлен');
}

function handleUpdateReview($db, $data) {
    // Проверка авторизации
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Необходимо авторизоваться');
    }

    // Проверка данных
    if (!isset($data['review_id']) || !isset($data['rating']) || !isset($data['review_text'])) {
        throw new Exception('Неверные данные');
    }

    $review_id = (int)$data['review_id'];
    $rating = (int)$data['rating'];
    $review_text = trim($data['review_text']);

    // Валидация
    if ($rating < 1 || $rating > 5) {
        throw new Exception('Некорректная оценка');
    }

    if (empty($review_text)) {
        throw new Exception('Текст отзыва не может быть пустым');
    }

    // Проверяем, принадлежит ли отзыв пользователю
    $checkQuery = $db->prepare("SELECT product_id FROM Reviews WHERE review_id = ? AND user_id = ?");
    $checkQuery->execute([$review_id, $_SESSION['user_id']]);
    $review = $checkQuery->fetch();

    if (!$review) {
        throw new Exception('Отзыв не найден или не принадлежит вам');
    }

    // Обновляем отзыв
    $updateQuery = $db->prepare("
        UPDATE Reviews 
        SET rating = ?, review_text = ?, created_at = NOW()
        WHERE review_id = ?
    ");
    $updateQuery->execute([$rating, $review_text, $review_id]);
    
    // Обновляем средний рейтинг товара
    updateProductRating($db, $review['product_id']);
    
    sendJsonResponse(true, 'Отзыв успешно обновлен');
}

function handleDeleteReview($db, $data) {
    // Проверка авторизации
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Необходимо авторизоваться');
    }

    // Проверка данных
    if (!isset($data['review_id'])) {
        throw new Exception('Неверные данные');
    }

    $review_id = (int)$data['review_id'];

    // Проверяем, принадлежит ли отзыв пользователю
    $checkQuery = $db->prepare("SELECT product_id FROM Reviews WHERE review_id = ? AND user_id = ?");
    $checkQuery->execute([$review_id, $_SESSION['user_id']]);
    $review = $checkQuery->fetch();

    if (!$review) {
        throw new Exception('Отзыв не найден или не принадлежит вам');
    }

    // Удаляем отзыв
    $deleteQuery = $db->prepare("DELETE FROM Reviews WHERE review_id = ?");
    $deleteQuery->execute([$review_id]);
    
    // Обновляем средний рейтинг товара
    updateProductRating($db, $review['product_id']);
    
    sendJsonResponse(true, 'Отзыв успешно удален');
}

function handleGetReviews($db, $params) {
    if (!isset($params['product_id'])) {
        throw new Exception('Не указан ID товара');
    }

    $product_id = (int)$params['product_id'];
    $page = isset($params['page']) ? (int)$params['page'] : 1;
    $sort = isset($params['sort']) ? $params['sort'] : 'newest';

    // Определяем порядок сортировки
    switch ($sort) {
        case 'newest':
            $orderBy = 'r.created_at DESC';
            break;
        case 'highest':
            $orderBy = 'r.rating DESC, r.created_at DESC';
            break;
        case 'lowest':
            $orderBy = 'r.rating ASC, r.created_at DESC';
            break;
        default:
            $orderBy = 'r.created_at DESC';
    }

    $reviewsPerPage = 5;
    $offset = ($page - 1) * $reviewsPerPage;

    // Получаем отзывы
    $reviewsQuery = $db->prepare("
        SELECT r.*, u.first_name, u.last_name 
        FROM Reviews r
        JOIN Users u ON r.user_id = u.user_id
        WHERE r.product_id = ?
        ORDER BY $orderBy
        LIMIT ? OFFSET ?
    ");
    $reviewsQuery->execute([$product_id, $reviewsPerPage, $offset]);
    $reviews = $reviewsQuery->fetchAll(PDO::FETCH_ASSOC);

    // Получаем общее количество отзывов
    $countQuery = $db->prepare("SELECT COUNT(*) as total FROM Reviews WHERE product_id = ?");
    $countQuery->execute([$product_id]);
    $total = $countQuery->fetchColumn();

    // Генерируем HTML
    $html = '';
    if (count($reviews) > 0) {
        foreach ($reviews as $review) {
            $html .= '<div class="review-item">';
            $html .= '<div class="review-header">';
            $html .= '<div>';
            $html .= '<span class="review-author">' . htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) . '</span>';
            $html .= '<div class="review-rating">';
            for ($i = 1; $i <= 5; $i++) {
                $html .= $i <= $review['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
            }
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="review-date">' . date('d.m.Y', strtotime($review['created_at'])) . '</div>';
            $html .= '</div>';
            $html .= '<div class="review-text-container">';
            $html .= '<div class="review-text">' . nl2br(htmlspecialchars($review['review_text'])) . '</div>';
            $html .= '</div>';
            
            // Проверяем, является ли текущий пользователь автором отзыва
            if (isset($_SESSION['user_id']) && $review['user_id'] == $_SESSION['user_id']) {
                $html .= '<div style="margin-top: 10px; text-align: right;">';
                $html .= '<button class="add-review-btn" onclick="openEditReviewModal(' . $review['review_id'] . ', ' . $product_id . ', \'' . htmlspecialchars(addslashes($review['product_name'])) . '\', ' . $review['rating'] . ', `' . htmlspecialchars(addslashes($review['review_text'])) . '`)">';
                $html .= '<i class="fas fa-edit"></i> Редактировать';
                $html .= '</button>';
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }
    } else {
        $html = '<div class="no-reviews"><p>Пока нет отзывов об этом товаре</p></div>';
    }

    sendJsonResponse(true, '', [
        'html' => $html,
        'total' => $total
    ]);
}

function handleGetReviewsCount($db, $params) {
    if (!isset($params['product_id'])) {
        throw new Exception('Не указан ID товара');
    }

    $product_id = (int)$params['product_id'];

    $countQuery = $db->prepare("SELECT COUNT(*) as count FROM Reviews WHERE product_id = ?");
    $countQuery->execute([$product_id]);
    $count = $countQuery->fetchColumn();

    sendJsonResponse(true, '', ['count' => $count]);
}

// Вспомогательные функции

function updateProductRating($db, $product_id) {
    $updateRatingQuery = $db->prepare("
        UPDATE Products 
        SET average_rating = (
            SELECT AVG(rating) FROM Reviews WHERE product_id = ?
        )
        WHERE product_id = ?
    ");
    $updateRatingQuery->execute([$product_id, $product_id]);
}

function sendJsonResponse($success, $message = '', $data = []) {
    $response = ['success' => $success];
    if ($message) $response['message'] = $message;
    if ($data) $response = array_merge($response, $data);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
?>