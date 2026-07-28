<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!-- Футер -->
<footer class="footer">
    <div class="container">
        <div class="footer-logo">
            <span class="logo-text">BirchBark</span>
        </div>
        
        <div class="footer-grid">
            <div>
                <h3>О компании</h3>
                <a href="onas.php">О нас</a>
            </div>
            
            <div>
                <h3>Помощь</h3>
                <a href="faq.php">Вопрос-ответ</a>
                <a href="garantia.php">Гарантия</a>
            </div>            
            <div>
                <h3>Информация</h3>
                <a href="dostavka.php">Доставка</a>
                <a href="oplata.php">Оплата</a>
                <a href="contacti.php">Контакты</a>
                <a href="recviziti.php">Реквизиты</a>
            </div>
        </div>
        
        <div class="copyright">
             &copy; <?= date('Y') ?> BirchBark. Все права защищены.
        </div>
    </div>
</footer>

<!-- Уведомление -->
<div id="notification" class="notification" style="display: none;">
    <i class="fas fa-check-circle"></i>
    <span id="notification-message">Товар добавлен в корзину</span>
</div>

<style>
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
}

.container {
    flex: 1;
}

.footer {
    background-color: var(--primary-color);
    color: white;
    padding: 40px 0 20px;
    margin-top: auto;
}

.footer-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 30px;
    gap: 15px;
}

.footer-icon {
    width: 40px;
    height: 40px;
    object-fit: contain;
    filter: brightness(0) invert(1);
    transition: transform 0.3s ease;
}

.footer-icon:hover {
    transform: scale(1.1);
}

.logo-text {
    font-family: 'Cormorant Garamond', serif;
    font-size: 24px;
    font-weight: 600;
    letter-spacing: 1px;
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
    font-family: 'Cormorant Garamond', serif;
    font-size: 18px;
}

.footer a {
    color: white;
    text-decoration: none;
    display: block;
    margin: 10px 0;
    transition: transform 0.2s ease;
}

.footer a:hover {
    text-decoration: underline;
    transform: translateX(5px);
}

.copyright {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.2);
    font-size: 14px;
    opacity: 0.8;
}

/* Стили для уведомлений */
.notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 1000;
    transform: translateY(100px);
    opacity: 0;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.notification.show {
    transform: translateY(0);
    opacity: 1;
}

.notification i {
    font-size: 20px;
}

.light-theme .notification {
    background-color: white;
    color: var(--primary-color);
    border: 1px solid var(--primary-color);
}

.dark-theme .notification {
    background-color: #333;
    color: var(--secondary-color);
    border: 1px solid var(--secondary-color);
}

/* Адаптивность */
@media (max-width: 768px) {
    .footer-logo {
        flex-direction: column;
        text-align: center;
    }
    
    .logo-text {
        margin-top: 10px;
    }
    
    .footer-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}
</style>

<script>
// Улучшенная функция с отладкой
function addToCart(productId, event) {
    event.preventDefault();
    event.stopPropagation();
    console.log('Попытка добавить товар ID:', productId); // Логирование

    // Проверка существования формы
    const formId = `add-form-${productId}`;
    const form = document.getElementById(formId);
    
    if (!form) {
        console.error('Форма не найдена:', formId);
        showNotification('Ошибка: форма не найдена', false);
        return;
    }

    // AJAX-запрос с обработкой ошибок
    fetch('index.php', {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Ошибка сети');
        return response.json();
    })
    .then(data => {
        console.log('Ответ сервера:', data);
        if (data.success) {
            showNotification(data.message || 'Товар добавлен в корзину');
            // Обновляем счетчик корзины, если он есть
            updateCartCounter();
        } else {
            showNotification(data.message || 'Ошибка при добавлении', false);
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showNotification('Серверная ошибка: ' + error.message, false);
    });
}

// Функция для показа уведомления
function showNotification(message, isSuccess = true) {
    const notification = document.getElementById('notification');
    const notificationMessage = document.getElementById('notification-message');
    const icon = notification.querySelector('i');
    
    notificationMessage.textContent = message;
    
    if (isSuccess) {
        icon.className = 'fas fa-check-circle';
    } else {
        icon.className = 'fas fa-exclamation-circle';
    }
    
    notification.style.display = 'flex';
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.style.display = 'none';
        }, 300);
    }, 3000);
}

// Показать уведомление из сессии, если оно есть
<?php if (isset($_SESSION['notification'])): ?>
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('<?= addslashes($_SESSION['notification']) ?>');
    });
    <?php unset($_SESSION['notification']); ?>
<?php endif; ?>

// Функция обновления счетчика корзины
function updateCartCounter() {
    const counter = document.querySelector('.cart-counter');
    if (counter) {
        counter.textContent = parseInt(counter.textContent || 0) + 1;
    }
}
</script>