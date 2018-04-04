<?php

return [
    "db" => [
        "username" => "root", //имя пользователя
        "password" => "", //пароль
        "host" => "localhost", //хост
        "dbname" => "loftschool" //название базы данных
    ],
    "resizePhoto" => [
        "photoName" => "turtleorigin.jpg", //название файла с фотографией для сжатия
        "resizePhotoName" => "resizephoto.jpg", //имя файда со сжатой картинкай
        "textPhoto" => "Привет Мир!" //Текс доя расмещения на фотографии
    ],
    "phpMailer" => [
        "host" => "smtp.yandex.ru", //адрес прчты откуда отправляем
        "username" => "asdfrfk@yandex.ru", //имя отправителя
        "password" => "lkjejml12348" //пароль
    ],
    "recaptcha" => [
        "sitekey" => "6Ld0MVAUAAAAAE9z7jgUnAyTcnQ3ZBUIoCLgjEf3",
        "secret" => "6Ld0MVAUAAAAAE-JwV29wOdxK-yQ78EudNkjFXrp"
    ]
];