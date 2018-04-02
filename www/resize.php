<?php
require_once (__DIR__.DIRECTORY_SEPARATOR."../vendor/autoload.php");
use \Intervention\Image\ImageManagerStatic as image;
$image = image::make("turtle_origin.jpg")
    ->resize(200, null, function ($image){
        $image->aspectRatio(); //сохранение пропорций
    })
    ->rotate(45) //поварачиваем картинку на 45 градусов
    ->save("turtle.jpg", 80);

$img = Image::make("turtle.jpg");
$img->text('Привет Мир!', $image->width()/2, $image->height()/2, function($font) {
    $font->file('arial.ttf');
    $font->size(28);
    $font->color(array(255, 0, 0, 0.5));
    $font->align('center');
    $font->valign('center');
    $font->angle(45); //поварачиваем текст на 45 градусов
});
$img->save("turtle.jpg");
header("Location: /photo_resize");