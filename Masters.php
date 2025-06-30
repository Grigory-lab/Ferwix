<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferwix</title>
    <link rel="stylesheet" href="main.css"> <!-- подключение css -->
</head>
<body>
    <header class="header"> <!-- шапка сайта -->
        <div class="container header__content">
            <img src="img/logo.png" alt="Лого" width="100px" height="100px">
            <nav class="menu">
                <ul class="menu__list">
                    <li class="menu__item"><a class="menu__link" href="Main.php">Главная</a></li>
                    <li class="menu__item"><a class="menu__link" href="Masters.php">Мастера</a></li>
                    <li class="menu__item"><a class="menu__link" href="Services.php">Услуги</a></li>
                    <li class="menu__item"><a class="menu__link" href="Callback.php">Обратная связь</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main container-2">
        <div class="hint" id="hint"><div class="hint-text">Здесь вы сможете узнать о наших мастерах.</div></div> <!-- блок подсказки -->
        <section class="standart-block2"> <!-- секция с блоками мастеров -->
            <h1>Наши мастера</h1>
            <div class="master-block">
                <img src="img/m 1.png" alt="Мастер" width="170px" height="170px">
                <div class="master-info">
                    <h2>Левский Максим Александрович</h2>
                    <p>
                        Специализация: парикмахер-стилист <br>
                        Опыт: 7 лет <br>
                        «Делаю мужские причёски, блондирование волос, хмическую и био-завивки, карвинг волос любой степени сложности. Являюсь специалистом в окрашивании волос.»
                    </p>
                </div>
            </div>
            <div class="line-m"><hr noshade></div>
            <div class="master-block">
                <img src="img/m 2.png" alt="Мастер" width="170px" height="170px">
                <div class="master-info">
                    <h2>Винивский Олег Владимирович</h2>
                    <p>
                        Специализация: косметолог <br>
                        Опыт: 5 лет <br>
                        «Занимаюсь очищением кожи лица, термолифтингом и микротоковой терапией. Провожу химические и аппаратные пилинги.»
                    </p>
                </div>
            </div>
            <div class="line-m"><hr noshade></div>
            <div class="master-block">
                <img src="img/m 3.png" alt="Мастер" width="170px" height="170px">
                <div class="master-info">
                    <h2>Авенская Мария Викторовна</h2>
                    <p>
                        Специализация: мастер маникюра <br>
                        Опыт: 20 лет <br>
                    «Занимаюсь маникюром: покрытие гель-лаком, арт-дизайн любой сложности, матовый топ, покрытие облачное, полировка ногтей.»
                    </p>
                </div>
            </div>
            <div class="line-m"><hr noshade></div>
            <div class="master-block">
                <img src="img/m 4.png" alt="Мастер" width="170px" height="170px">
                <div class="master-info">
                    <h2>Андревская Лиза Сергеевна</h2>
                    <p>
                        Специализация: парикмахер-стилист <br>
                        Опыт: 23 лет <br>
                        «Делаю различные мужские и женские причёски, включая андеркад, фейд, бокс, принстон, классическая, британка и полубокс. Провожу окрашивание и мелирование волос.»
                    </p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer"> <!-- подвал сайта -->
        <div class="container-2">
            <p>Ferwix 2025</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => { //событие при загрузке страницы
            const hint = document.getElementById('hint'); //переменная для получения элемента подсказки по id
            const pageKey = 'hintShown_' + window.location.pathname; //уникальный ключ страницы, для определения была ли показана подсказка

            if (!sessionStorage.getItem(pageKey)) { //если ключ не задан в сессии, то к подсказке добавляется класс, активирующий стиль анимации, а ключ задаётся в сессии
                hint.classList.add('animate');
                sessionStorage.setItem(pageKey, 'true');
            }
        });
    </script>
</body>
</html>