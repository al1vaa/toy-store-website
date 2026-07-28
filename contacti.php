<?php
session_start();
include 'header.php';
?>

<section class="contacts-section">
    <div class="container">
        <h1 class="section-title">Наши контакты</h1>
        
        <div class="contacts-grid">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h2>Адрес офиса</h2>
                <p>г. Калининград, ул. Тельмана, 32</p>
                <p><small>Часы работы: Пн-Пт 9:00-18:00</small></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <h2>Склад</h2>
                <p>г. Неман, ул. Краснoармейская, 11</p>
                <p><small>Часы работы: Пн-Пт 9:00-18:00, Сб 10:00-15:00</small></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h2>Телефон</h2>
                <p><a href="tel:89114844685">8 (911) 484-46-85</a></p>
                <p><small>Звонки принимаем ежедневно с 8:00 до 20:00</small></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h2>Email</h2>
                <p><a href="mailto:al1vaa@mail.ru">al1vaa@mail.ru</a></p>
                <p><small>Отвечаем в течение 1 рабочего дня</small></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fab fa-telegram"></i>
                </div>
                <h2>Telegram</h2>
                <p><a href="https://t.me/witchonhunt" target="_blank">@witchonhunt</a></p>
                <p><small>Быстрые ответы в мессенджере</small></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h2>Режим работы</h2>
                <p>Пн-Пт: 9:00-18:00</p>
                <p>Сб: 10:00-15:00</p>
            </div>
        </div>
        
        <div class="contact-map">
            <h2>Мы на карте</h2>
            <div class="map-container">
                <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p&amp;source=constructor" width="100%" height="400" frameborder="0"></iframe>
            </div>
        </div>
        
        <div class="contact-form-section">
            <h2>Напишите нам</h2>
            <form id="contact-form" class="contact-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Ваше имя</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Сообщение</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Отправить сообщение</button>
            </form>
        </div>
    </div>
</section>

<style>
.contacts-section {
    padding: 60px 0;
    background-color: var(--bg-light);
}

.dark-theme .contacts-section {
    background-color: var(--bg-dark);
}

.contacts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin: 40px 0;
}

.contact-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
}

.dark-theme .contact-card {
    background: #333;
}

.contact-card:hover {
    transform: translateY(-5px);
}

.contact-icon {
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

.contact-card h2 {
    color: var(--primary-color);
    margin-bottom: 15px;
}

.dark-theme .contact-card h2 {
    color: var(--secondary-color);
}

.contact-card p {
    margin-bottom: 5px;
}

.contact-card a {
    color: var(--text-light);
    text-decoration: none;
    transition: color 0.3s;
}

.dark-theme .contact-card a {
    color: var(--text-dark);
}

.contact-card a:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

.contact-card small {
    color: #666;
    font-size: 14px;
}

.dark-theme .contact-card small {
    color: #aaa;
}

.contact-map {
    margin: 50px 0;
}

.map-container {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-top: 20px;
}

.contact-form-section {
    margin-top: 50px;
}

.contact-form {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.dark-theme .contact-form {
    background: #333;
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    flex: 1;
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
}

.dark-theme .form-group input,
.dark-theme .form-group textarea {
    background-color: #444;
    border-color: #555;
    color: white;
}

.form-group textarea {
    resize: vertical;
    min-height: 150px;
}

.submit-btn {
    display: block;
    width: 100%;
    padding: 15px;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.submit-btn:hover {
    background-color: #4a613d;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    
    if(contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(contactForm);
            
            fetch('send_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Сообщение успешно отправлено! Мы свяжемся с вами в ближайшее время.');
                    contactForm.reset();
                } else {
                    alert('Произошла ошибка: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при отправке формы');
            });
        });
    }
    
    // Маска для телефона
    const phoneInput = document.getElementById('phone');
    if(phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            let formattedValue = '';
            
            if(value.length > 0) {
                formattedValue += '(' + value.substring(0, 3);
            }
            if(value.length > 3) {
                formattedValue += ') ' + value.substring(3, 6);
            }
            if(value.length > 6) {
                formattedValue += '-' + value.substring(6, 8);
            }
            if(value.length > 8) {
                formattedValue += '-' + value.substring(8, 10);
            }
            
            this.value = formattedValue;
        });
    }
});
</script>

<?php include 'footer.php'; ?>