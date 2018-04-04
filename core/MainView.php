<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 03.04.2018
 * Time: 14:15
 */

namespace App;

use Twig_Environment;

class MainView
{
    protected $twig;
    protected $loader;

    public function __construct($data = [])
    {
        $dirViews = __DIR__ . '/../views';
        $this->loader = new \Twig_Loader_Filesystem($dirViews);
        $this->twig = new Twig_Environment($this->loader);
    }

    public function twigLoad($fileName, $data = [])
    {
        echo $this->twig->render($fileName . '.twig', $data);
    }

    public function render($fileName, $data = null)
    {
        if (!empty($data)) {
            extract($data);
        }
        require_once __DIR__ . '/../views/' . $fileName . '.php';
    }
}