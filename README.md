<h2 align="center">Universal contacts form with recaptcha</h2>

<p>Универсальная форма обратной связи, созданная мною для быстрой и легкой интеграции на любой сайт, будь то cms или самописка.</p> 
<p>Особенностью является универсальный обработчик, который все параметры принемает из html формы, в нём не нужно прописывать email'ы, поля и т.д. В данной версии с интегрированной reCaptcha необходимо лишь ввести секретный ключ капчи.</p>
<p>Отдельная версия без reCaptcha находится в соседнем репозитории.</p>

## Содержание

1. **[Install](#install)**
2. **[HTML](#html)**
3. **[CSS](#css)**
4. **[JS](#js)**
5. **[PHP](#php)**

---

## Install

> **Примечание:** Можно посмотреть [demo](http://dev.arahort.pro/demo-forms/index.php), с примером интеграции нескольких форм.

<p>Подключаем файлы</p>

```html
<head>
    <!--Стили формы-->
    <link href="/QupeForm/css/QupeForm.css" rel="stylesheet">
    <!--Если отсутствует F.A.-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <!--Если отсутствует jQuery-->
    <script src="https://code.jquery.com/jquery-1.11.3.min.js" type="text/javascript"></script>
    <!--Если отсуствует плагин маски-->
    <script src="/QupeForm/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <!--Google reCaptcha-->
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script>
        // Рендер нескольких рекапч на одной страницы, для каждой формы свой ID
        var grecaptcha1;
        var grecaptcha2;
        var grecaptcha3;
        var onloadCallback = function() {
            grecaptcha1 = grecaptcha.render(document.getElementById('grecaptcha1'), {
                'sitekey' : 'PUBLIC_KEY'
            });
            grecaptcha2 = grecaptcha.render(document.getElementById('grecaptcha2'), {
                'sitekey' : 'PUBLIC_KEY'
            });
            grecaptcha3 = grecaptcha.render(document.getElementById('grecaptcha3'), {
                'sitekey' : 'PUBLIC_KEY'
            });

        };
    </script>
    <!--Скрипты формы-->
    <script src="/QupeForm/js/QupeForm.js" type="text/javascript"></script>
</head>
```

---

## HTML

```html
<div class="fruitModal" id="modal1">
    <form class="fruitForm">
        <span class="fruitModal-close"><i class="fa fa-times"></i></span>
        <!-- Hidden settings -->
        <input type="hidden" name="project_name" value="Alex Arahort Portal">
        <input type="hidden" name="admin_email" value="alex@arahort.pro">
        <input type="hidden" name="form_subject" value="Форма обратной связи">
        <!-- END Hidden settings -->
        <fieldset>
            <legend class="legend">Обратный звонок</legend>
            <div class="input">
                <input name="Имя" type="text" placeholder="Имя*" required  />
                <span><i class="fa fa-user"></i></span>
            </div>
            <div class="input">
                <input name="Телефон" type="text" class="fruitPhone" placeholder="Телефон*" required />
                <span><i class="fa fa-phone"></i></span>
            </div>
            <div class="input">
                <input name="Email" type="email" placeholder="Email"/>
                <span><i class="fa fa-envelope"></i></span>
            </div>
            <div class="input">
                <textarea name="Сообщение" placeholder="Сообщение"></textarea>
                <span><i class="fa fa-pencil-square"></i></span>
            </div>
            <div class="input">
                <div class="grecaptcha-wrap" id="grecaptcha1"></div>
            </div>
            <input type="hidden" name="Страница" value="<?php echo "https://site.ru" . $_SERVER['REQUEST_URI'] ?>">
            <button type="submit" class="submit" onclick="yaCounterXXXXXXX.reachGoal('xxxxxxx'); return true;"><i class="fa fa-check"></i></button>
        </fieldset>
        <div class="fruitSuccess">Сообщение успешно отправлено <br />Наш менеджер свяжется с вами в ближайшее время</div>
    </form>
</div>
```
>  **fruitModal:** Класс отвечающий за обёртку модального окна.

>  **modal1:** ID для ссылки-вызова, например:
```html
<a href="#modal1" onclick="yaCounterXXXX.reachGoal('XXXXX'); return true;">Открыть форму</a>
```
>  **fruitForm:** класс-обёртка формы, к нему привязана отправка и всё остальное:

>  **project_name:** Хедер письма

>  **admin_email:** Емейл на который будет отправлено письмо

>  **form_subject:** Название сообщения письма

>  **name="XXX":** Во всех полях имя отвечает за название строки в теле письма, поэтому пишем на русском

>  **fruitPhone:** Класс отвечающий за добавление маски телефона

>  **grecaptcha-wrap:** Класс обёртки рекапчи, помимо этого класса каждой форме надо задать уникальный ID который будет указан в скрипте рендера рекапчи

```js
var grecaptcha1;
var onloadCallback = function() {
    grecaptcha1 = grecaptcha.render(document.getElementById('grecaptcha1'), {
        'sitekey' : 'PUBLIC_KEY'
    });
};
```
```html
<div class="input">
    <div class="grecaptcha-wrap" id="grecaptcha1"></div>
</div>
```
>  **name="Страница":** Если в форма в php файле - оставляем поле и меняем https://site.ru на адрес нашего сайте, если форма в хмтл и нет возможности передать адрес страницы - убераем поле, если это например Modx - можно вместо php кода использовать в value="плейсхолдеры modx"

>  **onclick:** Цели Яндекс.Метрики, GA итд, замените XXXX на свой ID и название цели.

---

## CSS

<p>При написании стилей я использовал Less препроцессор, вы можете использовать и изменять уже скомпилированный css файл, однако</p>

```less
@formColor: #color;
```

<p>позволяет быстро менять цвет формы под цвет сайта</p>

---

## JavaScript

<p>В QupeForm.js стоит обратить внимание на строку</p>

```js
url: "/QupeForm/mail.php",
```

<p>убедитесь что путь к файлу-обработчику указан верно.</p>

---

## PHP

<p>В php обработчике вы должны ввести секретный ключ reCaptcha:</p>

```php
const GOOGLE_RECAPTCHA_PRIVATE_KEY = 'SECRET_KEY';
```

<p>Все остальные дополнительные параметры расписаны в самом файле-обработчике.</p>

---

## Возможные проблемы и способы их решения

<h3>1. Иконки</h3>
<p>Если у вас подключён Font-Awesome, но вместо иконок все равно отображаются квадраты - проблема в версиях FA, в разные версиях FA классы имеют разное название, в новых версиях FA у разных типов иконок разные первые классы (fas, fab итд) у старых же все начинается с fa. Для того что бы посмотреть поддерживается ли иконка в вашей версии FA откройте исходный код вашего FA-css файла и посмотрите присутсвует ли там класс иконки, так же попробуйте поменять первый класс.<br>
Если вы подключаете последнюю версию FA, то актуальные версии иконок можно посмотреть тут https://fontawesome.com/icons?d=gallery&m=free 
</p>

<h3>2. Письма не приходят</h3>
<p>Если вам кажется что вы всё сделали правильно, но письма всё равно не приходят - проверьте работает ли на хостинге php функция mail(). Подробнее как это сделать вы можете посмотреть на сайте https://arahort.pro/blog/proverka-raboty-funktsii-mail-na-php/</p>

<h3>3. Ошибки в JavaScript</h3>
<p>Для начала убедитесь что у вас установлен jQuery, формы не тестировались на всех версиях jQuery, поэтому допускаю возможность проблем на очень старых версиях и.т.д.</p>

---

## Автор

**[Alex Arahort](https://arahort.pro/)**

---

**[⬆ наверх](#Содержание)**

