<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $url = 'https://api.openweathermap.org/data/2.5/weather?lat=43.3211301&lon=21.8959232&appid=60825efadeb08154a146559d1016ff34';
        $response = file_get_contents($url);
        return  "<h3>Ovo dolazi iz index controllera.</h3>" . $response . "<br>";
    }
}