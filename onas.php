<?php
session_start();
include 'header.php';
?>

<section class="about-section">
    <div class="container">
        <h1 class="section-title">О компании BirchBark</h1>
        
        <div class="about-content">
            <div class="about-image">
                <img src="images/about/workshop.jpg" alt="Наше производство">
            </div>
            
            <div class="about-text">
                <p>Компания BirchBark была основана в 2010 году с целью предоставления высококачественных пиломатериалов и изделий из дерева. Название компании отражает нашу связь с природой и натуральными материалами.</p>
                
                <h2>Наш ассортимент</h2>
                <ul class="features-list">
                    <li><i class="fas fa-check-circle"></i> Пиломатериалы: вагонка, блок-хаус, имитация бруса, планкен</li>
                    <li><i class="fas fa-check-circle"></i> Строганные изделия: брус, доска пола, брусок</li>
                    <li><i class="fas fa-check-circle"></i> Листовые материалы: ДВП, ОСБ плиты</li>
                    <li><i class="fas fa-check-circle"></i> Услуги по обработке древесины</li>
                </ul>
                
                <h2>Только лучшая древесина</h2>
                <p>Мы работаем с проверенными поставщиками из экологически чистых регионов России. Наша древесина проходит тщательный отбор и специальную обработку для обеспечения долговечности и сохранения природных свойств.</p>
                
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">13+</div>
                        <div class="stat-label">лет на рынке</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">довольных клиентов</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">видов продукции</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="principles-section">
            <h2>Наши принципы</h2>
            
            <div class="principles-grid">
                <div class="principle-card">
                    <div class="principle-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Квалифицированные сотрудники</h3>
                    <p>Наша команда состоит из опытных мастеров с многолетним стажем работы с деревом.</p>
                </div>
                
                <div class="principle-card">
                    <div class="principle-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Индивидуальный подход</h3>
                    <p>Мы учитываем все пожелания клиента и готовы выполнить нестандартные заказы.</p>
                </div>
                
                <div class="principle-card">
                    <div class="principle-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h3>Доступные цены</h3>
                    <p>Мы предлагаем оптимальное соотношение цены и качества без скрытых платежей.</p>
                </div>
                
                <div class="principle-card">
                    <div class="principle-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Постоянное развитие</h3>
                    <p>Мы регулярно обновляем ассортимент и внедряем новые технологии обработки.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.about-section {
    padding: 60px 0;
    background-color: var(--bg-light);
}

.dark-theme .about-section {
    background-color: var(--bg-dark);
}

.section-title {
    text-align: center;
    margin-bottom: 40px;
    font-size: 36px;
    color: var(--primary-color);
}

.dark-theme .section-title {
    color: var(--secondary-color);
}

.about-content {
    display: flex;
    gap: 40px;
    margin-bottom: 60px;
    align-items: center;
}

.about-image {
    flex: 1;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.about-image img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.5s;
}

.about-image:hover img {
    transform: scale(1.05);
}

.about-text {
    flex: 1;
}

.about-text h2 {
    color: var(--primary-color);
    margin-top: 25px;
    margin-bottom: 15px;
    font-size: 24px;
}

.dark-theme .about-text h2 {
    color: var(--secondary-color);
}

.features-list {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.features-list li {
    margin-bottom: 10px;
    padding-left: 30px;
    position: relative;
}

.features-list i {
    color: var(--primary-color);
    position: absolute;
    left: 0;
    top: 3px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 30px;
}

.stat-item {
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.dark-theme .stat-item {
    background: #333;
}

.stat-number {
    font-size: 32px;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 5px;
}

.dark-theme .stat-number {
    color: var(--secondary-color);
}

.stat-label {
    font-size: 14px;
    color: #666;
}

.dark-theme .stat-label {
    color: #aaa;
}

.principles-section {
    margin-top: 60px;
}

.principles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.principle-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.dark-theme .principle-card {
    background: #333;
}

.principle-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.principle-icon {
    width: 60px;
    height: 60px;
    background-color: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 24px;
}

.principle-card h3 {
    margin-bottom: 15px;
    color: var(--primary-color);
}

.dark-theme .principle-card h3 {
    color: var(--secondary-color);
}

@media (max-width: 768px) {
    .about-content {
        flex-direction: column;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'footer.php'; ?>