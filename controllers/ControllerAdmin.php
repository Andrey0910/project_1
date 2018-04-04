<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 03.04.2018
 * Time: 20:41
 */

namespace App;


class ControllerAdmin extends MainController
{
    public function index()
    {
        $modelAdmin = new ModelAdmin();
        //Получаем все данные о пользовптелях и заказах.
        $data['users'] = $modelAdmin->getAll();
        $file = 'admin';
        $this->view->twigLoad($file, $data);
    }
}