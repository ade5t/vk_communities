<?php
session_start();
if (!isset($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}
if (!isset($_SESSION['access_token'])){
    $_SESSION['access_token'] = 0;
}
if (!isset($_SESSION['communities'])){
    $_SESSION['communities'] = 0;
}
session_write_close();
?>
<!doctype html>
<html lang="ru">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/my.js"></script>

    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
    <script src="js/moment-with-locales.min.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>

    <title>Управление сообществами</title>
</head>

<body>
    <nav class="navbar navbar-default" style="background-color: lightgray">
        <a class="navbar-brand" style="color: lightslategrey" href="/vk/">Управление сообществами</a>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php if ( $_SESSION['user_id'] == 0): ?>
                    <li><a href="#" style="color: lightslategrey" onclick="login()">Войти через VK</a></li>
                <?php else: ?>
                    <li><a href="#" style="color: lightslategrey" onclick="logout()"><?php echo 'Выйти (id='.$_SESSION['user_id'].')'?></a></li>
                <?php endif ?>
            </ul>
        </div>
    </nav>

    <?php if ($_SESSION['user_id'] == 0): ?>
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <h2 class="mt-5">Добро пожаловать на сервис управления сообществами ВКонтакте!</h2>
                    <p><h4>Здесь вы сможете воспользоваться сервисом отображения сообществ, выбрать которые Вы сможете при помощи различных фильтров.</h4></p>
                    <p><h4>Авторизуйтесь на сайте, чтобы начать!</h4></p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <p><h4>Вы можете воспользоваться двумя фильтрами для выборки сообществ: фильтром по дате последнего опубликованного поста и фильтром по количеству подписчиков в сообществе.</h4></p>
                    <p><h4>Если Вы оставите одно из полей или оба поля пустыми, то они не будут учитываться при составлении выборки.</h4></p>
                    <p><h4>Также обратите внимание, что фильтры могут работать совместно. Это значит, что в выборку попадут только те сообщества, где последний пост был опубликован позже той даты, которую Вы
                    ввели, и где количество подписчиков больше введенного Вами числа.</h4></p>
                    <br>
                </div>
            </div>
        </div>

        <div id="filter" class="container" align="center">
            <p><h2>Загружаем данные...</h2></p>
        </div>

        <div class="container">
<!--            Вызов js-функции на заполнение таблицы-->
            <script>get_communities()</script>

            <div class="row">
                <div class="col-8">
                    <table id="tab" class="table table-striped table-sm">
                        <thead >
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Ваши сообщества</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif ?>
</body>

<!--                Коротокий адрес: --><?php //echo $_SESSION['communities']["response"]["items"][0]["screen_name"];?>
<!--                Кол-во подписчиков: --><?php //echo $_SESSION['communities']["response"]["items"][0]["members_count"];?>
<!--                Дата послденего поста: --><?php //echo $_SESSION['communities']["response"]["items"][0]["date"];