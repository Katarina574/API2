<?php

use Phalcon\Mvc\Controller;

class UploadController extends Controller
{
    public function indexAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ime = $_POST["ime"];
//            echo $ime;
        } else {
            echo "Morate poslati zahtev preko forme.";
        }
    }
}