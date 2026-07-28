<?php
session_start();
include 'header.php';
?>

<section class="delivery-section">
    <div class="container">
        <h1 class="section-title">Доставка и самовывоз</h1>
        
        <div class="delivery-methods">
            <div class="method-card">
                <div class="method-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h2>Доставка по Калининграду</h2>
                <ul>
                    <li>Стоимость: от 500 руб.</li>
                    <li>Сроки: 1-2 рабочих дня</li>
                    <li>Минимальный заказ: 5 000 руб.</li>
                    <li>Грузчики: 200 руб./этаж</li>
                </ul>
            </div>
            
            <div class="method-card">
                <div class="method-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h2>Доставка по области</h2>
                <ul>
                    <li>Стоимость: от 1 000 руб.</li>
                    <li>Сроки: 1-3 рабочих дня</li>
                    <li>Минимальный заказ: 10 000 руб.</li>
                    <li>Точная стоимость рассчитывается индивидуально</li>
                </ul>
            </div>
            
            <div class="method-card">
                <div class="method-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <h2>Самовывоз</h2>
                <ul>
                    <li>Адрес: г. Неман, ул. Краснoармейская, 11</li>
                    <li>Часы работы: Пн-Пт 9:00-18:00, Сб 10:00-15:00</li>
                    <li>Бесплатно</li>
                    <li>Помощь с погрузкой</li>
                </ul>
            </div>
        </div>
        
        <div class="delivery-map">
            <h2>Наши склады и офис</h2>
            <div class="map-container">
                <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p&amp;source=constructor" width="100%" height="400" frameborder="0"></iframe>
            </div>
            
            <div class="location-cards">
                <div class="location-card">
                    <h3>Склад в Немане</h3>
                    <p><i class="fas fa-map-marker-alt"></i> ул. Краснoармейская, 11</p>
                    <p><i class="fas fa-phone"></i> 8 (911) 484-46-85</p>
                    <p><i class="fas fa-clock"></i> Пн-Пт: 9:00-18:00, Сб: 10:00-15:00</p>
                </div>
                
                <div class="location-card">
                    <h3>Офис в Калининграде</h3>
                    <p><i class="fas fa-map-marker-alt"></i> ул. Тельмана, 32</p>
                    <p><i class="fas fa-phone"></i> 8 (911) 484-46-85</p>
                    <p><i class="fas fa-clock"></i> Пн-Пт: 9:00-18:00</p>
                </div>
            </div>
        </div>
        
        <div class="delivery-terms">
            <h2>Условия доставки</h2>
            
            <div class="terms-grid">
                <div class="term-item">
                    <h3><i class="fas fa-box-open"></i> Упаковка</h3>
                    <p>Все товары тщательно упаковываются для сохранности при транспортировке.</p>
                </div>
                
                <div class="term-item">
                    <h3><i class="fas fa-calendar-check"></i> Сроки</h3>
                    <p>Доставка осуществляется в согласованные с клиентом дату и временной интервал.</p>
                </div>
                
                <div class="term-item">
                    <h3><i class="fas fa-receipt"></i> Документы</h3>
                    <p>При доставке вы получаете полный пакет документов на товар.</p>
                </div>
                
                <div class="term-item">
                    <h3><i class="fas fa-check-circle"></i> Проверка</h3>
                    <p>Рекомендуем проверять товар при получении на соответствие заказу.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.delivery-section {
    padding: 60px 0;
    background-color: var(--bg-light);
}

.dark-theme .delivery-section {
    background-color: var(--bg-dark);
}

.delivery-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin: 40px 0;
}

.method-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.dark-theme .method-card {
    background: #333;
}

.method-card:hover {
    transform: translateY(-5px);
}

.method-icon {
    width: 70px;
    height: 70px;
    background-color: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 30px;
    margin: 0 auto 20px;
}

.method-card h2 {
    text-align: center;
    color: var(--primary-color);
    margin-bottom: 20px;
}

.dark-theme .method-card h2 {
    color: var(--secondary-color);
}

.method-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.method-card li {
    margin-bottom: 10px;
    padding-left: 25px;
    position: relative;
}

.method-card li:before {
    content: "•";
    color: var(--primary-color);
    position: absolute;
    left: 0;
    font-size: 20px;
}

.delivery-map {
    margin: 50px 0;
}

.map-container {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.location-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.location-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.dark-theme .location-card {
    background: #333;
}

.location-card h3 {
    color: var(--primary-color);
    margin-top: 0;
    margin-bottom: 20px;
}

.dark-theme .location-card h3 {
    color: var(--secondary-color);
}

.location-card p {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.location-card i {
    color: var(--primary-color);
    width: 20px;
    text-align: center;
}

.delivery-terms {
    margin-top: 50px;
}

.terms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.term-item {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.dark-theme .term-item {
    background: #333;
}

.term-item h3 {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--primary-color);
    margin-top: 0;
    margin-bottom: 15px;
}

.dark-theme .term-item h3 {
    color: var(--secondary-color);
}

.term-item i {
    font-size: 20px;
}

@media (max-width: 768px) {
    .delivery-methods {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'footer.php'; ?>