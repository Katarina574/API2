<?php

use Phalcon\Mvc\Controller;

class UploadController extends Controller
{
    public function indexAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ime = $_POST["ime"];
            $prezime = $_POST["prezime"];
            $mejl = $_POST["mejl"];
//            echo $ime;
            //save to database
            $korisnik = new Korisnik();
            $korisnik->ime=$ime;
            $korisnik->prezime=$prezime;
            $korisnik->mejl=$mejl;

            $success = $korisnik->save();

            if ($success) {
                $response = [
                    'success' => true,
                    'message' => 'Thanks for registering!'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Greska pri cuvanju korisnika.'
                ];
            }

//            $response = [
//                'success' => true,
//                'message' => 'Uspesno ste poslali ime, prezime i mejl: ' . $ime . $prezime . $mejl
//            ];

            return $this->response->setJsonContent($response);
        } else {
            echo "Morate poslati zahtev preko forme.";
        }
    }
}