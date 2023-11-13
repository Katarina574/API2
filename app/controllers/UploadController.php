<?php

use Phalcon\Mvc\Controller;

class UploadController extends Controller
{
    public function indexAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["ime"]) && !empty($_POST["prezime"]) && !empty($_POST["mejl"]) && !empty($_FILES['file']['tmp_name'])) {
            $ime = $_POST["ime"];
            $prezime = $_POST["prezime"];
            $mejl = $_POST["mejl"];

            $tmpFilePath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $fileData = file_get_contents($tmpFilePath);
            $fileSize = $_FILES['file']['size']; //vel datoteke u bajtima
            $fileSizeInMB = $fileSize / (1024 * 1024); //konverzija u megabajte

            //vrednost za temperaturu uzimam od api-ja
            $url = 'https://api.openweathermap.org/data/2.5/weather?lat=43.3211301&lon=21.8959232&appid=60825efadeb08154a146559d1016ff34';
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            $tempK = $data['main']['temp'];
            $tempC = $tempK - 273.15; //pretvori u celzijuse

            if ($fileSizeInMB < 5) {
                //save to database
                $korisnik = new Korisnik();
                $korisnik->ime = $ime;
                $korisnik->prezime = $prezime;
                $korisnik->mejl = $mejl;
                $korisnik->temperatura = $tempC;
                $korisnik->file = $fileData;

                $success = $korisnik->save();

                if ($success) {
                    $response = [
                        'success' => true,
                        'message' => "Fajl uspesno sacuvan."
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Greska pri cuvanju korisnika.'
                    ];
                }
            } else {
                $response = [
                    'message' => 'Fajl mora biti manji od 5MB. Trenutni ima velicinu: ' . $fileSizeInMB
                ];
            }
            return $this->response->setJsonContent($response);
        } else {
            $response = [
                'message' => 'Morate popuniti sva polja.'
            ];
        }
        return $this->response->setJsonContent($response);
    }
}