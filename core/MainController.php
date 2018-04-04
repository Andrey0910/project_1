<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 03.04.2018
 * Time: 14:11
 */

namespace App;

class MainController
{
    protected $view;

    public function __construct()
    {
        $this->view = new MainView();
    }
}