<?php
namespace App\Services;
use Phalcon\Http\Request;
use App\Repositories\UserRepository;

class FileService
{
    private UserService $userService;
    private UserRepository $userRepository;

    public function __construct(UserService $userService, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
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
    public function processAndSaveFile($file, $ime, $prezime, $mejl)
    {
        if ($this->requiredFieldsNotEmpty($ime, $prezime, $mejl, $file)) {
            $tempC = $this->getTemperatureFromApi();
            $fileName = $file->getName();
            $filePath = '/API2/Fajlovi/' . $fileName;

            try {
                $file->moveTo($filePath);
                $success = $this->saveToDatabase($ime, $prezime, $mejl, $tempC, $fileName, $filePath);

                if ($success) {
                    return [
                        'success' => true,
                        'message' => 'Korisnik uspešno sačuvan.',
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Greška prilikom čuvanja korisnika.'
                    ];
                }
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'message' => 'Greška prilikom obrade datoteke: ' . $e->getMessage()
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Nisu ispunjeni svi obavezni podacii.'
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

    private function saveToDatabase($ime, $prezime, $mejl, $tempC, $fileName, $filePath)
    {
        $this->userService->saveUser($ime, $prezime, $mejl, $tempC, $fileName, $filePath);
        return true;
    }
}
