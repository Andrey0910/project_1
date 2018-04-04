<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 03.04.2018
 * Time: 21:04
 */

namespace App;

use Intervention\Image\ImageManagerStatic as image;

class ControllerPhoto extends MainController
{
    protected $photoName;
    protected $resizePhotoName;
    protected $textPhoto;

    public function __construct()
    {
        parent::__construct();
        // подключаем настройки для изменения фотографии.
        $config = include(__DIR__ . DIRECTORY_SEPARATOR . '../models/config.php');
        $resizePhoto = (object)$config["resizePhoto"];
        $this->photoName = $resizePhoto->photoName;
        $this->resizePhotoName = $resizePhoto->resizePhotoName;
        $this->textPhoto = $resizePhoto->textPhoto;
    }

    public function index()
    {
        $fileName = 'photoorigin';
        $data = ['img' => '../img/' . $this->photoName];
        try {
            $this->view->twigLoad($fileName, $data);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function resizePhoto()
    {
        try {
            $image = image::make("../www/img/" . $this->photoName)
                ->resize(200, null, function ($image) {
                    $image->aspectRatio(); //сохранение пропорций
                })
                ->rotate(45);//поварачиваем картинку на 45 градусов
            $image->text($this->textPhoto, $image->width() / 2, $image->height() / 2, function ($font) {
                $font->file('fonts/arial.ttf');
                $font->size(28);
                $font->color(array(255, 0, 0, 0.5));
                $font->align('center');
                $font->valign('center');
                $font->angle(45); //поварачиваем текст на 45 градусов
            });
            $image->save("../www/img/" . $this->resizePhotoName, 80);
            $viewName = 'resizephoto';
            $data = ['img' => '../img/' . $this->resizePhotoName];
            $this->view->twigLoad($viewName, $data);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function deleteResizePhoto()
    {
        try {
            $fullPath = __DIR__ . DIRECTORY_SEPARATOR . "../www/img/" . $this->resizePhotoName;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            header("Location: /photo");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}