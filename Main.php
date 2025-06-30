<?php
    include "DB.php"; //включение файла с подключением к БД
    $entered = 0;
    $ea = 0;

    if (isset($_SESSION['login-a'])) { //если переменная с логином установлена, то переменная entered становится 1
        $login = $_SESSION['login-a'];
        $entered = 1;
        if ($login == 'admin') { //проверка, вошёл ли админ в аккаунт
            $ea = 1;
        }
    }
    elseif (isset($_SESSION['login-r']))
    {
        $login = $_SESSION['login-r'];
        $entered = 1;
    }
?>

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
        <div class="auth-block"> <!-- блок навигации (слева) -->
            <div class="auth">
                <p>Личный кабинет</p>
                <button class="btn" id="auth"><a href="Auth.php">Авторизоваться</a></button>
                <button class="btn" id="reg"><a href="Registration.php">Зарегистрироваться</a></button>
                <button class="btn entr" id="enter"><a href="Account.php">Открыть</a></button>
            </div>
            <div class="admin-panel" id="adm">
                <button class="btn admin"><a href="Admin_panel.php">Админ-панель</a></button>
            </div>
        </div>

        <section class="about"> <!-- блок с информацией о компании -->
            <h1>О компании</h1>
            <p>
                Салон красоты <strong>Ferwix</strong> — это пространство, где сочетаются стиль,
                забота и профессионализм. Мы работаем, чтобы каждый гость почувствовал себя
                особенным, уверенным и вдохновлённым. Наша команда — это сертифицированные мастера
                с многолетним опытом в индустрии красоты. Мы следим за трендами, используем только
                проверенные косметические бренды и стремимся к тому, чтобы каждый визит в наш салон
                приносил удовольствие. Независимо от того, приходите ли вы за лёгкой стрижкой, ярким
                окрашиванием, маникюром или расслабляющим уходом за лицом — вы в надёжных руках.
            </p>
        </section>
        <div class="line"><hr noshade></div>
        <section class="why-us"> <!-- блок "почему выбирают нас" -->
            <h1>Почему выбирают нас</h1>
            <div class="why-us__content">
                <div class="why-us__block">
                    <p>
                        Наш салон красоты выбирают те, кто ценит качество, внимание к деталям и искреннюю заботу.
                        В центре нашего подхода — индивидуальность. Мы уверены, что у каждой внешности есть
                        своя особенная изюминка, и наша задача — подчеркнуть её с помощью профессионального
                        ухода, стильных решений и глубокого понимания пожеланий клиента.
                    </p>
                    <img src="img/m_img_1.png" alt="Салон 1">
                </div>
                <div class="why-us__block second">
                    <img src="img/m_img_2.png" alt="Салон 2">
                    <p>
                        Особое внимание мы уделяем атмосфере: наш интерьер продуман до мелочей, чтобы вы
                        могли расслабиться, отвлечься от повседневной суеты и почувствовать, что находитесь
                        в месте, где действительно заботятся о вашем комфорте.
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
        let entered = <?php echo json_encode($entered); ?>; //преобразование переменной для JS
        let ea = <?php echo json_encode($ea); ?>;

        if (entered === 1) { //если entered = 1, то кнопки авторизации и регистрации заменяются на кнопку входа в личный кабинет
            document.getElementById("auth").style.display = "none";
            document.getElementById("reg").style.display = "none";
            document.getElementById("enter").style.display = "block";
        }

        if (ea === 1) { //если ea = 1, то появится кнопка админ панели
            document.getElementById("adm").style.display = "flex";
        }
    </script>
</body>
</html>