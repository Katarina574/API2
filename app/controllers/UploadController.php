<?php

use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

class UploadController extends Controller
{
    public function indexAction()
    {
        $response = new Response();
        $request = new Request();

        if ($request->isPost() && $this->requiredFieldsNotEmpty()) {
            $files = $this->request->getUploadedFiles('file');

            if (count($files) === 1) {
                $file = $files[0];
                $fileSize = $file->getSize();
                $fileSizeInMB = $fileSize / (1024 * 1024);
                if ($fileSizeInMB < 5) {
                    $response = $this->processAndSaveData($file);
                } else {
                    $response = [
                        'message' => 'Svi fajlovi moraju biti manji od 5MB, trenutna velicina fajla je: ' . $fileSizeInMB
                    ];
                }
            } else {
                $response = [
                    'message' => 'Molim unesite samo jedan fajl.'
                ];
            }
        } else {
            $response = [
                'message' => 'Sva polja moraju biti popunjena.'
            ];
        }

        return $this->response->setJsonContent($response);
    }

    private function requiredFieldsNotEmpty()
    {
        $request = new Request();
        return (
            !empty($request->getPost("ime")) &&
            !empty($request->getPost("prezime")) &&
            !empty($request->getPost("mejl")) &&
            $request->hasFiles()
        );
    }

    private function processAndSaveData($file)
    {
        $ime = $this->request->getPost("ime");
        $prezime = $this->request->getPost("prezime");
        $mejl = $this->request->getPost("mejl");
        $fileName = $file->getName();
        $tempC = $this->getTemperatureFromApi();
        $fileName = $file->getName();
        $filePath = '/API2/Fajlovi/'. $fileName;
        $file->moveTo($filePath);

        $success = $this->saveToDatabase($ime, $prezime, $mejl, $tempC, $fileName, $filePath, $file);

        if ($success) {
            return [
                'success' => true,
                'message' => 'Korisnik uspesno sacuvan. ',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Greska prilikom cuvanja korisnika.'
            ];
        }
    }

    private function getTemperatureFromApi()
    {
        $url = 'https://api.openweathermap.org/data/2.5/weather?lat=43.3211301&lon=21.8959232&appid=60825efadeb08154a146559d1016ff34';
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        $tempK = $data['main']['temp'];
        return round($tempK - 273.15);
    }

    private function saveToDatabase($ime, $prezime, $mejl, $tempC, $fileName, $filePath, $file)
    {
        $korisnik = new Korisnik();
        $korisnik->ime = $ime;
        $korisnik->prezime = $prezime;
        $korisnik->mejl = $mejl;
        $korisnik->temperatura = $tempC;
        $korisnik->file = $filePath;

        return $korisnik->save();
    }
}
