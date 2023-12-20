<?php
namespace App\Services;
use Phalcon\Http\Request;
use App\Exceptions\FileUploadException;
use App\Repositories\UserRepository;

require_once __DIR__ . '/../exceptions/FileUploadException.php';

class FileService
{
    private UserService $userService;
    private UserRepository $userRepository;
    private WeatherService $weatherService;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->userService = new UserService($userRepository);
        $this->weatherService = new WeatherService();
    }
    private function requiredFieldsNotEmpty($ime, $prezime, $mejl, $file)
    {
        $request = new Request();
        return (
            !empty($ime) &&
            !empty($prezime) &&
            !empty($mejl) &&
            $request->hasFiles()
        );
    }

    /**
     * @throws FileUploadException
     */
    public function processAndSaveFile($file, $ime, $prezime, $mejl)
    {
        if (!$this->requiredFieldsNotEmpty($ime, $prezime, $mejl, $file)) {
            throw new FileUploadException('Nisu ispunjeni svi obavezni podaci.');
        }

        $tempC = $this->weatherService->getTemperature();
        $fileName = $file->getName();
        $filePath = BASE_PATH . '/Fajlovi/' . $fileName;

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
                'message' => 'Greška! ' . $e->getMessage()
            ];
        }
    }

    private function saveToDatabase($ime, $prezime, $mejl, $tempC, $fileName, $filePath)
    {
        $korisnik = $this->userService->saveUser($ime, $prezime, $mejl, $tempC, $fileName, $filePath);

        if ($korisnik === false) {
            return [
                'success' => false,
                'message' => 'Greška prilikom čuvanja korisnika.',
            ];
        }
        return true;
    }

}

