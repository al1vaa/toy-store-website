<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!-- Верхняя панель с услугами -->
<div class="top-bar">
    <div class="container flex-container">
        <div class="services-menu">
             <a href="#" class="modal-link" data-modal="assembly-modal">Сборка и установка мебели</a>
             <a href="#" class="modal-link" data-modal="stairs-modal">Установка лестниц</a>
                <a href="#" class="modal-link" data-modal="cutting-modal">Технический распил материалов</a>
        </div>
        <div class="buy-menu">
                <a href="#" class="modal-link" data-modal="wholesale-modal">Оптовикам</a>
        </div>
    </div>
</div>

<!-- Основная шапка -->
<header class="header">
    <div class="container flex-container">
        <div class="logo">
            <a href="index.php">
                <h1>BirchBark</h1>
            </a>
        </div>
        <nav class="main-menu">
            <a href="catalog.php" class="catalog-link"><i class="fas fa-bars"></i> Каталог</a>
        </nav>
        <div class="search-container">
            <form action="search.php" method="get">
                <input type="text" name="query" class="search-input" placeholder="Поиск по названию или артикулу..." required>
                <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="user-actions">
            <button class="icon-btn" id="theme-toggle"><i class="fas fa-moon"></i></button>
            <a href="cart.php" class="icon-btn"><i class="fas fa-shopping-cart"></i></a>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['role_id'] == 777): ?>
                <a href="admin_panel.php" class="admin-panel-btn" title="Админ панель">
                    <i class="fas fa-cog"></i>
                </a>
            <?php endif; ?>
            <div class="user-dropdown">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-dropdown-content">
                        <?php if($_SESSION['role_id'] == 777): ?>
                            <!-- Меню для администратора -->
                            <a href="manage_orders.php"><i class="fas fa-shopping-cart"></i> Заказы</a>
                            <a href="manage_products.php"><i class="fas fa-boxes"></i> Товары</a>
                            <a href="add_product.php"><i class="fas fa-plus-circle"></i> Добавить товар</a>
                            <a href="add_category.php"><i class="fas fa-folder-plus"></i> Добавить категорию</a>
                            <a href="add_manufacturer.php"><i class="fas fa-industry"></i> Добавить производителя</a>
                            <div class="divider"></div>
                        <?php endif; ?>
                        <!-- Общие пункты для всех авторизованных пользователей -->
                        <a href="profile.php"><i class="fas fa-user-circle"></i> Профиль</a>
                        <a href="orders.php"><i class="fas fa-clipboard-list"></i> Мои заказы</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Выход</a>
                    </div>
                <?php else: ?>
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-dropdown-content">
                        <a href="login.php"><i class="fas fa-sign-in-alt"></i> Вход</a>
                        <a href="register.php"><i class="fas fa-user-plus"></i> Регистрация</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
    <!-- Модальные окна для хедера -->
<div id="assembly-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2><i class="fas fa-couch"></i> Сборка и установка мебели</h2>
        <div class="modal-body">
            <div class="service-features">
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    <h3>Быстро</h3>
                    <p>Стандартная сборка за 2-4 часа</p>
                </div>
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Надежно</h3>
                    <p>Гарантия на работы 1 год</p>
                </div>
                <div class="feature">
                    <i class="fas fa-tools"></i>
                    <h3>Профессионально</h3>
                    <p>Опытные мастера с профессиональным инструментом</p>
                </div>
            </div>

            <h3 class="service-subtitle">Мы собираем:</h3>
            <ul class="service-list">
                <li><i class="fas fa-check-circle"></i> Корпусную мебель (шкафы, комоды, кухни)</li>
                <li><i class="fas fa-check-circle"></i> Мягкую мебель (диваны, кресла, кровати)</li>
                <li><i class="fas fa-check-circle"></i> Офисную мебель</li>
                <li><i class="fas fa-check-circle"></i> Детскую мебель</li>
                <li><i class="fas fa-check-circle"></i> Встроенные гарнитуры</li>
            </ul>

            <div class="price-examples">
                <h3 class="service-subtitle">Примеры стоимости:</h3>
                <table class="price-table">
                    <tr>
                        <td>Шкаф-купе (2 двери)</td>
                        <td>от 2500 руб.</td>
                    </tr>
                    <tr>
                        <td>Кухонный гарнитур (до 3м)</td>
                        <td>от 4000 руб.</td>
                    </tr>
                    <tr>
                        <td>Детская кровать</td>
                        <td>от 1500 руб.</td>
                    </tr>
                    <tr>
                        <td>Офисный стол</td>
                        <td>от 1000 руб.</td>
                    </tr>
                </table>
            </div>

            <div class="service-cta">
                <button class="service-order-btn" onclick="window.location.href='contacti.php'">
                    <i class="fas fa-calendar-alt"></i> Заказать сборку
                </button>
                <div class="service-phone">
                    <i class="fas fa-phone"></i> 8 (911) 484-46-85
                </div>
            </div>
        </div>
    </div>
</div>

<div id="stairs-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2><i class="fas fa-stairs"></i> Установка лестниц</h2>
        <div class="modal-body">
            <div class="service-features">
                <div class="feature">
                    <i class="fas fa-ruler-combined"></i>
                    <h3>Точно</h3>
                    <p>Погрешность не более 1мм</p>
                </div>
                <div class="feature">
                    <i class="fas fa-lock"></i>
                    <h3>Безопасно</h3>
                    <p>Соответствие ГОСТам</p>
                </div>
                <div class="feature">
                    <i class="fas fa-medal"></i>
                    <h3>Качественно</h3>
                    <p>Используем только проверенные материалы</p>
                </div>
            </div>

            <h3 class="service-subtitle">Типы лестниц:</h3>
            <div class="stairs-types">
                <div class="stairs-type">
                    <img src="images/stairs/marsh.jpg" alt="Маршевая лестница">
                    <h4>Маршевые</h4>
                </div>
                <div class="stairs-type">
                    <img src="images/stairs/vint.jpg" alt="Винтовая лестница">
                    <h4>Винтовые</h4>
                </div>
                <div class="stairs-type">
                    <img src="images/stairs/bolts.jpg" alt="Лестница на больцах">
                    <h4>На больцах</h4>
                </div>
            </div>

            <h3 class="service-subtitle">Этапы работы:</h3>
            <ol class="work-steps">
                <li>Замер и консультация</li>
                <li>Разработка проекта</li>
                <li>Изготовление элементов</li>
                <li>Доставка и монтаж</li>
                <li>Финишная обработка</li>
            </ol>

            <div class="service-cta">
                <button class="service-order-btn" onclick="window.location.href='contacti.php'">
                    <i class="fas fa-pencil-alt"></i> Заказать расчет
                </button>
            </div>
        </div>
    </div>
</div>

<div id="cutting-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2><i class="fas fa-cut"></i> Технический распил материалов</h2>
        <div class="modal-body">
            <div class="cutting-info">
                <div class="cutting-image">
                    <img src="images/cutting-machine.jpg" alt="Распиловочный станок">
                </div>
                <div class="cutting-details">
                    <h3>Мы режем:</h3>
                    <ul>
                        <li>Массив дерева (дуб, ясень, сосна, бук)</li>
                        <li>Фанеру (от 3мм до 30мм)</li>
                        <li>МДФ, ДСП, ЛДСП</li>
                        <li>Мебельные щиты</li>
                        <li>OSB, ХДФ</li>
                    </ul>
                </div>
            </div>

            <h3 class="service-subtitle">Технические возможности:</h3>
            <table class="specs-table">
                <tr>
                    <td>Максимальная длина реза</td>
                    <td>3000 мм</td>
                </tr>
                <tr>
                    <td>Максимальная толщина</td>
                    <td>50 мм</td>
                </tr>
                <tr>
                    <td>Точность</td>
                    <td>±0.5 мм</td>
                </tr>
                <tr>
                    <td>Минимальный заказ</td>
                    <td>5 листов или 3000 руб.</td>
                </tr>
            </table>

            <div class="price-calculator">
                <h3 class="service-subtitle">Калькулятор стоимости:</h3>
                <div class="calculator-form">
                    <div class="form-group">
                        <label>Материал:</label>
                        <select id="material-type">
                            <option value="pine">Сосна</option>
                            <option value="larch">Лиственница</option>
                            <option value="oak">Дуб</option>
                            <option value="spruce">Ель</option>
                            <option value="linden">Липа</option>
                            <option value="cedar">Кедр</option>
                            <option value="osb">OSB</option>
                            <option value="dvp">ДВП</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Толщина материала (мм):</label>
                        <input type="number" id="material-thickness" min="1" max="50" value="10">
                    </div>
                    <div class="form-group">
                        <label>Количество резов:</label>
                        <input type="number" id="cut-count" min="1" value="1">
                    </div>
                    <div class="form-group">
                        <label>Общая длина реза (м):</label>
                        <input type="number" id="total-length" min="0.1" step="0.1" value="1.0">
                    </div>
                    <button id="calculate-btn" class="calculate-btn">Рассчитать</button>
                    <div id="price-result" class="price-result">~ 0 руб.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="wholesale-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2><i class="fas fa-boxes"></i> Оптовикам</h2>
        <div class="modal-body">
            <div class="wholesale-benefits">
                <div class="benefit-card">
                    <i class="fas fa-percentage"></i>
                    <h3>Скидки до 25%</h3>
                    <p>При заказе от 100 000 руб.</p>
                </div>
                <div class="benefit-card">
                    <i class="fas fa-truck"></i>
                    <h3>Бесплатная доставка</h3>
                    <p>По Калининграду от 150 000 руб.</p>
                </div>
                <div class="benefit-card">
                    <i class="fas fa-file-contract"></i>
                    <h3>Индивидуальный договор</h3>
                    <p>Гибкие условия оплаты</p>
                </div>
            </div>

            <h3 class="service-subtitle">Условия сотрудничества:</h3>
            <div class="wholesale-conditions">
                <div class="condition">
                    <div class="condition-number">1</div>
                    <p>Минимальная сумма заказа - 50 000 руб.</p>
                </div>
                <div class="condition">
                    <div class="condition-number">2</div>
                    <p>Предоплата 30% для новых клиентов</p>
                </div>
                <div class="condition">
                    <div class="condition-number">3</div>
                    <p>Срок изготовления - от 3 рабочих дней</p>
                </div>
            </div>

            <h3 class="service-subtitle">Популярные оптовые позиции:</h3>
            <div class="wholesale-products">
                <div class="product-card">
                    <img src="images/products/board-oak.jpg" alt="Дубовая доска">
                    <h4>Дубовая доска</h4>
                    <div class="prices">
                        <span class="retail-price">1200 руб./м²</span>
                        <span class="wholesale-price">900 руб./м²</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="images/products/board-pine.jpg" alt="Сосновая доска">
                    <h4>Сосновая доска</h4>
                    <div class="prices">
                        <span class="retail-price">600 руб./м²</span>
                        <span class="wholesale-price">450 руб./м²</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="images/products/plywood.jpg" alt="Фанера">
                    <h4>Фанера 18мм</h4>
                    <div class="prices">
                        <span class="retail-price">1800 руб./лист</span>
                        <span class="wholesale-price">1400 руб./лист</span>
                    </div>
                </div>
            </div>

            <div class="wholesale-form">
                <h3 class="service-subtitle">Стать оптовым клиентом:</h3>
                <form id="wholesale-request">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Ваше имя:</label>
                            <input type="text" required>
                        </div>
                        <div class="form-group">
                            <label>Телефон:</label>
                            <input type="tel" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Компания:</label>
                        <input type="text" required>
                    </div>
                    <div class="form-group">
                        <label>Интересующие товары:</label>
                        <textarea rows="3"></textarea>
                    </div>
                    <button type="submit" class="wholesale-submit-btn">
                        <i class="fas fa-paper-plane"></i> Отправить заявку
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
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
body.dark-theme {
    background-color: var(--bg-dark);
    color: var(--text-dark);
}
.header {
    background-color: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 100;
    padding: 15px 0;
}
.dark-theme .header {
    background-color: #1a1a1a;
}
.top-bar {
    background-color: var(--primary-color);
    color: white;
    padding: 10px 0;
    font-size: 14px;
}
.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
}
.flex-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.logo {
    margin-right: auto;
}

.logo a {
    text-decoration: none;
    color: var(--primary-color);
    font-weight: bold;
    font-size: 24px;
}

.logo h1 {
    margin: 0; /* Убираем лишние отступы у заголовка */
}
.dark-theme .logo a {
    color: white;
}
.main-menu {
    order: 1;
}

.main-menu a {
    color: var(--text-light);
    text-decoration: none;
    font-weight: 500;
    padding: 10px 15px;
    white-space: nowrap;
}

.dark-theme .main-menu a {
    color: var(--text-dark);
}
.search-container {
    order: 2;
    margin: 0;
    flex-grow: 1;
    max-width: 400px;
    position: relative;
}
.search-input {
    width: 80%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 18px;
    font-size: 14px;
    outline: none;
}
.dark-theme .search-input {
    background-color: #444;
    border-color: #555;
    color: white;
}
.header .search-container .search-btn {
    position: absolute;
    right: 100px !important; 
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: none !important;
    border: none !important;
    cursor: pointer !important;
    color: var(--primary-color) !important;
}
.dark-theme .search-btn {
    color: var(--secondary-color);
}
.icon-btn {
    background: none;
    border: none;
    font-size: 25px;
    cursor: pointer;
    color: var(--text-light);
}
.dark-theme .icon-btn {
    color: var(--text-dark);
}
.user-actions {
    order: 3;
    display: flex;
    align-items: center;
    gap: 25px;
}
.user-dropdown {
    position: relative;
    display: inline-block;
}
.user-dropdown-content {
    display: none;
    position: absolute;
    left: 1px;
    background-color: white;
    min-width: 200px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 18px;
    padding: 10px 0;
}
.dark-theme .user-dropdown-content {
    background-color: #333;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.4);
}
.user-dropdown:hover .user-dropdown-content {
    display: block;
}
.user-dropdown-content a {
    color: #333;
    padding: 10px 20px;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s;
}
.dark-theme .user-dropdown-content a {
    color: #f0f0f0;
}
.user-dropdown-content a:hover {
    background-color: rgba(0,0,0,0.1);
}
.dark-theme .user-dropdown-content a:hover {
    background-color: rgba(255,255,255,0.1);
}
.user-avatar {
    display: flex;
    align-items: center;
    cursor: pointer;
}
.user-avatar i {
    font-size: 24px;
    margin-right: 8px;
}
/* Стили для верхней панели с услугами */
.top-bar a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
    transition: color 0.3s;
}
        
.top-bar a:hover {
    color: var(--secondary-color);
}

/* Стили для кнопки админ панели */
.admin-panel-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--text-light);
    text-decoration: none;
    font-size: 16px;
    transition: color 0.3s;
}

.dark-theme .admin-panel-btn {
    color: var(--text-dark);
}

.admin-panel-btn:hover {
    color: var(--secondary-color);
}

.admin-panel-btn i {
    font-size: 20px;
}

.admin-panel-text {
    font-size: 14px;
}
/* Стили модальных окон */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.7);
    opacity: 0;
    transition: opacity 0.3s;
}

.modal.show {
    opacity: 1;
}

.modal-content {
    background-color: var(--bg-light);
    margin: 5% auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    width: 80%;
    max-width: 900px;
    position: relative;
    transform: translateY(-50px);
    transition: transform 0.3s;
}

.modal.show .modal-content {
    transform: translateY(0);
}

.dark-theme .modal-content {
    background-color: var(--bg-dark);
    color: var(--text-dark);
}

.close-modal {
    position: absolute;
    right: 25px;
    top: 15px;
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s;
}

.close-modal:hover {
    color: var(--primary-color);
}

.modal h2 {
    color: var(--primary-color);
    font-family: 'Cormorant Garamond', serif;
    font-size: 28px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.dark-theme .modal h2 {
    color: var(--secondary-color);
    border-color: #444;
}

/* Стили для услуг */
.service-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.feature {
    text-align: center;
    padding: 20px;
    background: rgba(90, 114, 71, 0.1);
    border-radius: 10px;
    transition: transform 0.3s;
}

.dark-theme .feature {
    background: rgba(212, 167, 98, 0.1);
}

.feature:hover {
    transform: translateY(-5px);
}

.feature i {
    font-size: 30px;
    color: var(--primary-color);
    margin-bottom: 10px;
}

.dark-theme .feature i {
    color: var(--secondary-color);
}

.feature h3 {
    margin: 10px 0;
    font-size: 18px;
}

.service-subtitle {
    color: var(--primary-color);
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
    margin: 25px 0 15px;
}

.dark-theme .service-subtitle {
    color: var(--secondary-color);
}

.service-list {
    list-style: none;
    padding: 0;
}

.service-list li {
    padding: 8px 0;
    display: flex;
    align-items: center;
}

.service-list i {
    color: var(--primary-color);
    margin-right: 10px;
    font-size: 18px;
}

.dark-theme .service-list i {
    color: var(--secondary-color);
}

.price-table {
    width: 100%;
    border-collapse: collapse;
}

.price-table tr {
    border-bottom: 1px solid #eee;
}

.dark-theme .price-table tr {
    border-color: #444;
}

.price-table td {
    padding: 12px 0;
}

.price-table td:last-child {
    text-align: right;
    font-weight: bold;
    color: var(--primary-color);
}

.dark-theme .price-table td:last-child {
    color: var(--secondary-color);
}

.service-cta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.service-order-btn {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
}

.dark-theme .service-order-btn {
    background-color: var(--secondary-color);
    color: #333;
}

.service-order-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.service-phone {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 600;
}

.service-phone i {
    color: var(--primary-color);
}

.dark-theme .service-phone i {
    color: var(--secondary-color);
}

/* Специфические стили для разных модалок */
.stairs-types {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.stairs-type {
    text-align: center;
}

.stairs-type img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 10px;
}

.work-steps {
    list-style-type: none;
    counter-reset: step-counter;
    padding: 0;
}

.work-steps li {
    counter-increment: step-counter;
    margin-bottom: 15px;
    padding-left: 40px;
    position: relative;
}

.work-steps li:before {
    content: counter(step-counter);
    position: absolute;
    left: 0;
    top: 0;
    background-color: var(--primary-color);
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.dark-theme .work-steps li:before {
    background-color: var(--secondary-color);
    color: #333;
}

/* Стили для распила */
.cutting-info {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.cutting-image {
    flex: 1;
    min-width: 300px;
}

.cutting-image img {
    width: 100%;
    border-radius: 8px;
    max-height: 250px;
    object-fit: cover;
}

.cutting-details {
    flex: 1;
    min-width: 300px;
}

.cutting-details ul {
    list-style: none;
    padding: 0;
}

.cutting-details li {
    padding: 8px 0;
    display: flex;
    align-items: center;
}

.cutting-details li:before {
    content: "•";
    color: var(--primary-color);
    font-size: 20px;
    margin-right: 10px;
}

.dark-theme .cutting-details li:before {
    color: var(--secondary-color);
}

.specs-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

.specs-table tr {
    border-bottom: 1px solid #eee;
}

.dark-theme .specs-table tr {
    border-color: #444;
}

.specs-table td {
    padding: 12px 0;
}

.specs-table td:last-child {
    font-weight: bold;
    text-align: right;
}

/* Калькулятор стоимости */
.price-calculator {
    background: rgba(90, 114, 71, 0.05);
    padding: 20px;
    border-radius: 10px;
    margin-top: 30px;
}

.dark-theme .price-calculator {
    background: rgba(212, 167, 98, 0.05);
}

.calculator-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    align-items: end;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
}

.dark-theme .form-group input,
.dark-theme .form-group select {
    background-color: #444;
    border-color: #555;
    color: white;
}

.calculate-btn {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: background-color 0.3s;
}

.dark-theme .calculate-btn {
    background-color: var(--secondary-color);
    color: #333;
}

.calculate-btn:hover {
    opacity: 0.9;
}

.price-result {
    font-size: 24px;
    font-weight: bold;
    color: var(--primary-color);
    text-align: center;
    padding: 10px;
    background: white;
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.dark-theme .price-result {
    color: var(--secondary-color);
    background: #333;
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

/* Стили для оптовиков */
.wholesale-benefits {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.benefit-card {
    text-align: center;
    padding: 20px;
    background: rgba(90, 114, 71, 0.1);
    border-radius: 10px;
}

.dark-theme .benefit-card {
    background: rgba(212, 167, 98, 0.1);
}

.benefit-card i {
    font-size: 40px;
    color: var(--primary-color);
    margin-bottom: 15px;
}

.dark-theme .benefit-card i {
    color: var(--secondary-color);
}

.benefit-card h3 {
    margin: 10px 0;
    font-size: 18px;
}

.wholesale-conditions {
    margin: 20px 0;
}

.condition {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    gap: 15px;
}

.condition-number {
    background-color: var(--primary-color);
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
}

.dark-theme .condition-number {
    background-color: var(--secondary-color);
    color: #333;
}

.wholesale-products {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.product-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.dark-theme .product-card {
    background: #333;
    box-shadow: 0 3px 10px rgba(0,0,0,0.3);
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.product-card h4 {
    padding: 0 15px;
    margin: 10px 0;
}

.prices {
    padding: 0 15px 15px;
}

.retail-price {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
    display: block;
}

.wholesale-price {
    color: var(--primary-color);
    font-weight: bold;
    font-size: 18px;
}

.dark-theme .wholesale-price {
    color: var(--secondary-color);
}

.wholesale-form {
    margin-top: 30px;
}

.form-row {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.form-row .form-group {
    flex: 1;
    min-width: 200px;
}

.wholesale-submit-btn {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 15px;
    transition: all 0.3s;
}

.dark-theme .wholesale-submit-btn {
    background-color: var(--secondary-color);
    color: #333;
}

.wholesale-submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Адаптивность */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 10% auto;
        padding: 20px;
    }
    
    .service-features {
        grid-template-columns: 1fr;
    }
    
    .service-cta {
        flex-direction: column;
    }
    
    .stairs-types {
        grid-template-columns: 1fr;
    }
}

</style>
<script>
// Цены за метр реза для разных материалов (на основе данных из БД)
const cuttingPrices = {
    'pine': { base: 50, thicknessFactor: 0.5 },     // Сосна
    'larch': { base: 70, thicknessFactor: 0.7 },     // Лиственница
    'oak': { base: 100, thicknessFactor: 1.0 },      // Дуб
    'spruce': { base: 60, thicknessFactor: 0.6 },    // Ель
    'linden': { base: 80, thicknessFactor: 0.8 },    // Липа
    'cedar': { base: 120, thicknessFactor: 1.2 },    // Кедр
    'osb': { base: 40, thicknessFactor: 0.4 },       // OSB
    'dvp': { base: 30, thicknessFactor: 0.3 }        // ДВП
};

// Минимальная стоимость заказа
const MIN_ORDER_PRICE = 3000;

window.addEventListener('DOMContentLoaded', function () {
    // Темная тема
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            const icon = themeToggle.querySelector('i');
            if (document.body.classList.contains('dark-theme')) {
                icon.classList.replace('fa-moon', 'fa-sun');
                localStorage.setItem('theme', 'dark');
            } else {
                icon.classList.replace('fa-sun', 'fa-moon');
                localStorage.setItem('theme', 'light');
            }
        });

        // Установка темы при загрузке
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-theme');
            themeToggle.querySelector('i').classList.replace('fa-moon', 'fa-sun');
        } else {
            document.body.classList.remove('dark-theme');
            themeToggle.querySelector('i').classList.replace('fa-sun', 'fa-moon');
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Открытие модальных окон
    document.querySelectorAll('.modal-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const modalId = this.getAttribute('data-modal');
            openModal(modalId);
        });
    });

    // Закрытие модальных окон
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    // Закрытие при клике вне окна
    window.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal')) {
            closeModal();
        }
    });

    // Калькулятор стоимости распила
    const calculateBtn = document.getElementById('calculate-btn');
    if (calculateBtn) {
        calculateBtn.addEventListener('click', calculateCuttingPrice);
    }

    // Остановка всплытия события на кнопках
    document.querySelectorAll('.service-order-btn, .calculate-btn, .wholesale-submit-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });
});

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
    }
}

function closeModal() {
    document.querySelectorAll('.modal').forEach(modal => {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    });
}

function calculateCuttingPrice() {
    const materialType = document.getElementById('material-type').value;
    const thickness = parseInt(document.getElementById('material-thickness').value) || 10;
    const cuts = parseInt(document.getElementById('cut-count').value) || 1;
    const length = parseFloat(document.getElementById('total-length').value) || 1.0;

    const materialData = cuttingPrices[materialType] || cuttingPrices['pine'];
    const pricePerMeter = materialData.base * (1 + (thickness / 10) * materialData.thicknessFactor);
    let totalPrice = pricePerMeter * length + cuts * 10;
    totalPrice = Math.max(totalPrice, MIN_ORDER_PRICE);

    const formattedPrice = Math.round(totalPrice).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    document.getElementById('price-result').textContent = `~ ${formattedPrice} руб.`;
}
</script>
