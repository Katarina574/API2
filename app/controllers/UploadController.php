<?php

use Phalcon\Mvc\Controller;

class UploadController extends Controller
{
    public function indexAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ime = $_POST["ime"];
//            echo $ime;
            $response = [
                'success' => true,
                'message' => 'Uspesno ste poslali ime: ' . $ime
            ];

            return $this->response->setJsonContent($response);
        } else {
            echo "Morate poslati zahtev preko forme.";
        }
    }
}