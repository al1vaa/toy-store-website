<?php
session_start();
include 'header.php';
?>

<section class="warranty-section">
    <div class="container">
        <h1 class="section-title">Гарантийные обязательства</h1>
        
        <div class="warranty-content">
            <div class="warranty-text">
                <h2>Гарантия на продукцию</h2>
                <p>Мы уверены в качестве наших материалов и предоставляем гарантию на всю продукцию. Гарантийный срок зависит от вида товара и условий эксплуатации.</p>
                
                <div class="warranty-features">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Сроки гарантии</h3>
                            <p>Гарантия на пиломатериалы - от 1 года, на изделия из клееной древесины - до 5 лет.</p>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Условия гарантии</h3>
                            <p>Гарантия действует при соблюдении правил эксплуатации и наличии подтверждения покупки.</p>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Гарантийный ремонт</h3>
                            <p>В случае выявления производственного дефекта мы устраним его бесплатно.</p>
                        </div>
                    </div>
                </div>
                
                <h2>Гарантийные случаи</h2>
                <p>Гарантия распространяется на следующие случаи:</p>
                <ul class="warranty-list">
                    <li><i class="fas fa-check"></i> Трещины, возникшие не по вине покупателя</li>
                    <li><i class="fas fa-check"></i> Деформация материала при соблюдении условий хранения</li>
                    <li><i class="fas fa-check"></i> Расслоение клееных изделий</li>
                    <li><i class="fas fa-check"></i> Несоответствие заявленным характеристикам</li>
                </ul>
                
                <h2>Негарантийные случаи</h2>
                <p>Гарантия не распространяется, если:</p>
                <ul class="warranty-list negative">
                    <li><i class="fas fa-times"></i> Нарушены правила эксплуатации и хранения</li>
                    <li><i class="fas fa-times"></i> Изделие подвергалось неправильной установке</li>
                    <li><i class="fas fa-times"></i> Повреждения вызваны механическим воздействием</li>
                    <li><i class="fas fa-times"></i> Изделие изменялось или ремонтировалось самостоятельно</li>
                </ul>
            </div>
            
            <div class="warranty-image">
                <img src="images/warranty.jpg" alt="Гарантия качества">
            </div>
        </div>
        
        <div class="warranty-process">
            <h2>Процесс гарантийного обслуживания</h2>
            
            <div class="process-steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Обращение</h3>
                        <p>При обнаружении дефекта свяжитесь с нами по телефону, email или через форму на сайте в течение гарантийного срока.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Проверка</h3>
                        <p>Наш специалист проведет осмотр товара и определит, является ли случай гарантийным.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Решение</h3>
                        <p>В случае подтверждения гарантийного случая мы предложим варианты решения: ремонт, замену или возврат средств.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Исполнение</h3>
                        <p>Мы выполним принятое решение в кратчайшие сроки с минимальными неудобствами для вас.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="warranty-contacts">
            <h2>Контакты для гарантийных вопросов</h2>
            <p>По всем вопросам, связанным с гарантией, обращайтесь:</p>
            
            <div class="contacts-grid">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <p><strong>Телефон:</strong> <a href="tel:89114844685">8 (911) 484-46-85</a></p>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <p><strong>Email:</strong> <a href="mailto:al1vaa@mail.ru">al1vaa@mail.ru</a></p>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <p><strong>Адрес:</strong> г. Калининград, ул. Тельмана, 32</p>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <p><strong>Часы работы:</strong> Пн-Пт: 9:00-18:00, Сб: 10:00-15:00</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.warranty-section {
    padding: 60px 0;
    background-color: var(--bg-light);
}

.dark-theme .warranty-section {
    background-color: var(--bg-dark);
}

.warranty-content {
    display: flex;
    gap: 40px;
    margin-bottom: 50px;
}

.warranty-text {
    flex: 2;
}

.warranty-image {
    flex: 1;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    align-self: flex-start;
}

.warranty-image img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.5s;
}

.warranty-image:hover img {
    transform: scale(1.05);
}

.warranty-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.feature-card {
    display: flex;
    gap: 15px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.dark-theme .feature-card {
    background: #333;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-icon {
    width: 50px;
    height: 50px;
    background-color: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.feature-text h3 {
    margin-top: 0;
    margin-bottom: 10px;
    color: var(--primary-color);
}

.dark-theme .feature-text h3 {
    color: var(--secondary-color);
}

.warranty-list {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.warranty-list li {
    margin-bottom: 10px;
    padding-left: 30px;
    position: relative;
}

.warranty-list i {
    position: absolute;
    left: 0;
    top: 3px;
}

.warranty-list i.fa-check {
    color: #28a745;
}

.warranty-list.negative i.fa-times {
    color: #dc3545;
}

.warranty-process {
    margin: 50px 0;
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.step {
    position: relative;
    padding: 25px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.dark-theme .step {
    background: #333;
}

.step-number {
    position: absolute;
    top: -20px;
    left: 20px;
    width: 40px;
    height: 40px;
    background-color: var(--primary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 20px;
}

.step-content h3 {
    margin-top: 10px;
    color: var(--primary-color);
}

.dark-theme .step-content h3 {
    color: var(--secondary-color);
}

.warranty-contacts {
    margin-top: 50px;
}

.contacts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.dark-theme .contact-item {
    background: #333;
}

.contact-item i {
    font-size: 24px;
    color: var(--primary-color);
}

.dark-theme .contact-item i {
    color: var(--secondary-color);
}

.contact-item a {
    color: var(--text-light);
    text-decoration: none;
    transition: color 0.3s;
}

.dark-theme .contact-item a {
    color: var(--text-dark);
}

.contact-item a:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

@media (max-width: 768px) {
    .warranty-content {
        flex-direction: column;
    }
    
    .warranty-image {
        order: -1;
    }
}
</style>

<?php include 'footer.php'; ?>