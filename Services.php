<?php
    include "DB.php"; //включение файла с подключением к БД
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
        <div class="hint" id="hint"><div class="hint-text">Вам нужно авторизироваться, чтобы записаться на услугу.</div></div> <!-- блок подсказки -->
        <section class="standart-block2"> <!-- секция услуг -->
            <h1>Услуги</h1>
                <?php
                    $query = "SELECT * FROM services";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="master-block">';
                            echo '  <div class="service-im">';
                            echo '<img src="image.php?id=' . $row['Service_ID'] . '" alt="Услуга" width="170px" height="170px">';
                            echo '      <h2>' . htmlspecialchars($row['Price_rub']) . ' руб.</h2>';
                            echo '  </div>';
                            echo '  <div class="master-info">';
                            echo '      <h2>' . htmlspecialchars($row['Name']) . '</h2>';
                            echo '      <p>' . htmlspecialchars($row['Description']) . '</p>';
                            echo '  </div>';
                            echo '</div>';
                            echo '<div class="line-m"><hr noshade></div>';
                        }
                    } else {
                        echo '<p>Услуги не найдены.</p>';
                    }
                ?>
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