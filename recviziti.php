<?php
session_start();
include 'header.php';
?>

<section class="requisites-section">
    <div class="container">
        <h1 class="section-title">Реквизиты компании</h1>
        
        <div class="requisites-card">
            <h2>ООО "BirchBark"</h2>
            
            <div class="requisites-grid">
                <div class="requisite-item">
                    <h3>Юридический адрес</h3>
                    <p>238710, Россия, Калининградская обл., г. Неман, ул. Краснoармейская, 11</p>
                </div>
                
                <div class="requisite-item">
                    <h3>Фактический адрес</h3>
                    <p>236022, Россия, г. Калининград, ул. Тельмана, 32</p>
                </div>
                
                <div class="requisite-item">
                    <h3>ИНН</h3>
                    <p>3901234567</p>
                </div>
                
                <div class="requisite-item">
                    <h3>КПП</h3>
                    <p>390101001</p>
                </div>
                
                <div class="requisite-item">
                    <h3>ОГРН</h3>
                    <p>1234567890123</p>
                </div>
                
                <div class="requisite-item">
                    <h3>ОКПО</h3>
                    <p>98765432</p>
                </div>
                
                <div class="requisite-item">
                    <h3>Банк</h3>
                    <p>Филиал "Балтийский" ПАО "Сбербанк"</p>
                </div>
                
                <div class="requisite-item">
                    <h3>БИК</h3>
                    <p>042748634</p>
                </div>
                
                <div class="requisite-item">
                    <h3>Р/с</h3>
                    <p>40702810500000012345</p>
                </div>
                
                <div class="requisite-item">
                    <h3>К/с</h3>
                    <p>30101810000000000634</p>
                </div>
                
                <div class="requisite-item">
                    <h3>Генеральный директор</h3>
                    <p>Вибецките Алина Стасевна</p>
                </div>
            </div>
            
            <div class="requisites-download">
                <a href="docs/requisites.pdf" class="download-btn" download>
                    <i class="fas fa-file-pdf"></i> Скачать реквизиты (PDF)
                </a>
            </div>
        </div>
        
        <div class="documents-section">
            <h2>Наши документы</h2>
            
            <div class="documents-grid">
                <div class="document-card">
                    <div class="document-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Свидетельство о регистрации</h3>
                    <a href="docs/certificate.pdf" download class="document-link">Скачать PDF</a>
                </div>
                
                <div class="document-card">
                    <div class="document-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <h3>Выписка из ЕГРЮЛ</h3>
                    <a href="docs/egrul.pdf" download class="document-link">Скачать PDF</a>
                </div>
                
                <div class="document-card">
                    <div class="document-icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <h3>Доверенность на представителя</h3>
                    <a href="docs/proxy.pdf" download class="document-link">Скачать PDF</a>
                </div>
                
                <div class="document-card">
                    <div class="document-icon">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <h3>Образец договора</h3>
                    <a href="docs/contract_sample.pdf" download class="document-link">Скачать PDF</a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.requisites-section {
    padding: 60px 0;
    background-color: var(--bg-light);
}

.dark-theme .requisites-section {
    background-color: var(--bg-dark);
}

.requisites-card {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 50px;
}

.dark-theme .requisites-card {
    background: #333;
}

.requisites-card h2 {
    text-align: center;
    color: var(--primary-color);
    margin-bottom: 30px;
}

.dark-theme .requisites-card h2 {
    color: var(--secondary-color);
}

.requisites-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.requisite-item {
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
}

.dark-theme .requisite-item {
    background: #444;
}

.requisite-item h3 {
    color: var(--primary-color);
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 16px;
}

.dark-theme .requisite-item h3 {
    color: var(--secondary-color);
}

.requisite-item p {
    margin: 0;
    font-weight: 500;
}

.requisites-download {
    text-align: center;
    margin-top: 40px;
}

.download-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 25px;
    background-color: var(--primary-color);
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.3s;
}

.download-btn:hover {
    background-color: #4a613d;
}

.download-btn i {
    font-size: 20px;
}

.documents-section {
    margin-top: 50px;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.document-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
}

.dark-theme .document-card {
    background: #333;
}

.document-card:hover {
    transform: translateY(-5px);
}

.document-icon {
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

.document-card h3 {
    color: var(--primary-color);
    margin-bottom: 15px;
}

.dark-theme .document-card h3 {
    color: var(--secondary-color);
}

.document-link {
    display: inline-block;
    padding: 8px 15px;
    background-color: #f5f5f5;
    color: var(--primary-color);
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s;
}

.dark-theme .document-link {
    background-color: #444;
    color: var(--secondary-color);
}

.document-link:hover {
    background-color: var(--primary-color);
    color: white;
}

@media (max-width: 768px) {
    .requisites-card {
        padding: 25px;
    }
    
    .requisites-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'footer.php'; ?>