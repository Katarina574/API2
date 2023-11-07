<?php

use Phalcon\Mvc\Controller;

class UploadController extends Controller
{
    public function indexAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //pokupljam vrednosti
            $ime = $_POST["ime"];
            $prezime = $_POST["prezime"];
            $mejl = $_POST["mejl"];

            $tmpFilePath = $_FILES['file']['tmp_name'];
            $fileData = file_get_contents($tmpFilePath);

            //vrednost za temperaturu uzimam od api-ja
            $url = 'https://api.openweathermap.org/data/2.5/weather?lat=43.3211301&lon=21.8959232&appid=60825efadeb08154a146559d1016ff34';
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            //save to database
            $korisnik = new Korisnik();
            $korisnik->ime=$ime;
            $korisnik->prezime=$prezime;
            $korisnik->mejl=$mejl;
            $korisnik->temperatura = $data['main']['temp'];
            $korisnik->file=$fileData;

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

            return $this->response->setJsonContent($response);
        } else {
            echo "Morate poslati zahtev preko forme.";
        }
    }
}