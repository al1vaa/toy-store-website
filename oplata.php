<?php
session_start();
include 'header.php';
?>

<section class="payment-section">
    <div class="container">
        <h1 class="section-title">Способы оплаты</h1>
        
        <div class="payment-methods">
            <div class="method-card">
                <div class="method-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h2>Наличными</h2>
                <ul>
                    <li>При получении товара (доставка/самовывоз)</li>
                    <li>В нашем офисе в Калининграде</li>
                    <li>Без комиссии</li>
                    <li>Выдаем кассовый чек</li>
                </ul>
            </div>
            
            <div class="method-card">
                <div class="method-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h2>Банковской картой</h2>
                <ul>
                    <li>Visa, Mastercard, Мир</li>
                    <li>Онлайн при оформлении заказа</li>
                    <li>В нашем офисе через терминал</li>
                    <li>Безопасное соединение</li>
                </ul>
            </div>
            
            <div class="method-card">
                <div class="method-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h2>Безналичный расчет</h2>
                <ul>
                    <li>Для юридических лиц</li>
                    <li>Счет выставляется на email</li>
                    <li>НДС по требованию</li>
                    <li>Работаем с НДС и без</li>
                </ul>
            </div>
        </div>
        
        <div class="payment-process">
            <h2>Как происходит оплата</h2>
            
            <div class="process-steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Выбор способа</h3>
                        <p>При оформлении заказа выберите удобный способ оплаты.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Подтверждение</h3>
                        <p>Для онлайн-оплаты вас перенаправит на безопасную страницу платежной системы.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Оплата</h3>
                        <p>Введите данные карты или получите счет для оплаты.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Подтверждение</h3>
                        <p>После успешной оплаты вы получите уведомление на email.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="payment-security">
            <h2>Безопасность платежей</h2>
            
            <div class="security-content">
                <div class="security-text">
                    <p>Мы гарантируем безопасность всех онлайн-платежей. Данные вашей карты защищены по стандарту PCI DSS.</p>
                    <ul>
                        <li><i class="fas fa-lock"></i> Шифрование данных</li>
                        <li><i class="fas fa-shield-alt"></i> Защита от мошенничества</li>
                        <li><i class="fas fa-check-circle"></i> Без сохранения данных карты</li>
                    </ul>
                </div>
                
                <div class="security-badges">
                    <img src="images/payment/visa.png" alt="Visa Secure">
                    <img src="images/payment/mastercard.png" alt="Mastercard SecureCode">
                    <img src="images/payment/pci.png" alt="PCI DSS">
                </div>
            </div>
        </div>
        
        <div class="payment-faq">
            <h2>Частые вопросы об оплате</h2>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Как вернуть деньги за товар?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Возврат средств осуществляется на ту же карту, с которой была произведена оплата, в течение 3-10 рабочих дней после обработки запроса.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Есть ли комиссия за оплату картой?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Нет, мы не берем дополнительную комиссию за оплату банковской картой.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Можно ли оплатить заказ частично?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Да, для крупных заказов возможна оплата в 2 этапа: 50% при оформлении и 50% перед отгрузкой.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.payment-section {
    padding: 60px 0;
    background-color: var(--bg-light);
}

.dark-theme .payment-section {
    background-color: var(--bg-dark);
}

.payment-methods {
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

.payment-process {
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

.payment-security {
    margin: 50px 0;
}

.security-content {
    display: flex;
    gap: 40px;
    align-items: center;
    margin-top: 30px;
}

.security-text {
    flex: 1;
}

.security-text ul {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.security-text li {
    margin-bottom: 10px;
    padding-left: 30px;
    position: relative;
}

.security-text i {
    color: var(--primary-color);
    position: absolute;
    left: 0;
    top: 3px;
}

.security-badges {
    flex: 1;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.security-badges img {
    height: 50px;
    width: auto;
    object-fit: contain;
}

.payment-faq {
    margin-top: 50px;
}

.faq-item {
    margin-bottom: 15px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.dark-theme .faq-item {
    background: #333;
}

.faq-question {
    padding: 20px;
    background: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: background-color 0.3s;
}

.dark-theme .faq-question {
    background: #444;
}

.faq-question:hover {
    background-color: #f5f5f5;
}

.dark-theme .faq-question:hover {
    background-color: #555;
}

.faq-question h3 {
    margin: 0;
    font-size: 18px;
    color: var(--primary-color);
}

.dark-theme .faq-question h3 {
    color: var(--secondary-color);
}

.faq-question i {
    transition: transform 0.3s;
}

.faq-question.active i {
    transform: rotate(180deg);
}

.faq-answer {
    padding: 0;
    max-height: 0;
    overflow: hidden;
    background: white;
    transition: max-height 0.3s ease, padding 0.3s ease;
}

.dark-theme .faq-answer {
    background: #333;
}

.faq-item.active .faq-answer {
    padding: 20px;
    max-height: 500px;
}

.faq-answer p {
    margin: 0;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .payment-methods {
        grid-template-columns: 1fr;
    }
    
    .security-content {
        flex-direction: column;
    }
    
    .security-badges {
        margin-top: 30px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', () => {
            // Закрываем все открытые элементы
            faqItems.forEach(otherItem => {
                if(otherItem !== item && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                    otherItem.querySelector('.faq-answer').style.maxHeight = '0';
                    otherItem.querySelector('.faq-answer').style.padding = '0';
                }
            });
            
            // Открываем/закрываем текущий элемент
            item.classList.toggle('active');
            const answer = item.querySelector('.faq-answer');
            
            if(item.classList.contains('active')) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                answer.style.padding = '20px';
            } else {
                answer.style.maxHeight = '0';
                answer.style.padding = '0';
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?>