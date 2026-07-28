<?php
session_start();
include 'header.php';
?>

<section class="faq-section">
    <div class="container">
        <h1 class="section-title">Часто задаваемые вопросы</h1>
        
        <div class="faq-intro">
            <p>Здесь собраны ответы на самые популярные вопросы наших клиентов. Если вы не нашли ответ на свой вопрос, свяжитесь с нами:</p>
            
            <div class="contact-methods">
                <div class="contact-method">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-info">
                        <h3>Телефон</h3>
                        <p><a href="tel:89114844685">8 (911) 484-46-85</a></p>
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">
                        <i class="fab fa-telegram"></i>
                    </div>
                    <div class="contact-info">
                        <h3>Telegram</h3>
                        <p><a href="https://t.me/witchonhunt" target="_blank">@witchonhunt</a></p>
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-info">
                        <h3>Email</h3>
                        <p><a href="mailto:al1vaa@mail.ru">al1vaa@mail.ru</a></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="faq-accordion">
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Как выбрать подходящий пиломатериал?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Выбор пиломатериала зависит от целей использования. Для внутренней отделки подойдет вагонка из сосны или липы, для фасадов - лиственница или термообработанная древесина. Наши консультанты помогут подобрать оптимальный вариант для ваших нужд.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Нужна ли дополнительная обработка материалов?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Мы поставляем материалы, готовые к использованию. Однако для увеличения срока службы рекомендуется обработка защитными составами, особенно для наружного применения. В нашем ассортименте есть все необходимые защитные средства.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Как рассчитать необходимое количество материала?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Для точного расчета необходимо знать площадь покрытия и выбранный тип материала. На нашем сайте есть калькуляторы для каждого вида продукции. Также вы можете отправить нам план помещения, и мы сделаем расчет бесплатно.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Какие гарантии вы предоставляете?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Мы гарантируем соответствие продукции заявленным характеристикам. Гарантийный срок зависит от вида материала и условий эксплуатации. Подробнее о гарантиях можно узнать в соответствующем разделе нашего сайта.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Есть ли у вас услуги доставки?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Да, мы осуществляем доставку по Калининграду и области. Стоимость зависит от объема заказа и удаленности. Также возможен самовывоз со склада в Немане по адресу: Краснoармейская, 11.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Какие способы оплаты вы принимаете?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Мы принимаем наличные при самовывозе, банковские карты, безналичный расчет для юридических лиц. Подробнее о способах оплаты читайте в разделе "Оплата".</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Можно ли заказать нестандартные размеры?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Да, мы выполняем индивидуальные заказы. Сроки и стоимость изготовления нестандартных изделий рассчитываются отдельно. Для уточнения деталей свяжитесь с нашими менеджерами.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.faq-section {
    padding: 60px 0;
    background-color: var(--bg-light);
}

.dark-theme .faq-section {
    background-color: var(--bg-dark);
}

.faq-intro {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 40px;
    line-height: 1.6;
}

.contact-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.dark-theme .contact-method {
    background: #333;
}

.contact-method:hover {
    transform: translateY(-5px);
}

.contact-icon {
    width: 60px;
    height: 60px;
    background-color: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.contact-info h3 {
    margin-bottom: 5px;
    color: var(--primary-color);
}

.dark-theme .contact-info h3 {
    color: var(--secondary-color);
}

.contact-info a {
    color: var(--text-light);
    text-decoration: none;
    transition: color 0.3s;
}

.dark-theme .contact-info a {
    color: var(--text-dark);
}

.contact-info a:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

.faq-accordion {
    max-width: 800px;
    margin: 0 auto;
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

@media (max-width: 600px) {
    .contact-methods {
        grid-template-columns: 1fr;
    }
    
    .faq-question h3 {
        font-size: 16px;
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