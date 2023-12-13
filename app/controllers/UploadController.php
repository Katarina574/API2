<?php

use App\Services\FileService;
use App\Services\UserService;
use App\Services\WeatherService;
use App\Repositories\UserRepository;
use Phalcon\Http\Request;
use \Phalcon\Mvc\Controller;

class UploadController extends Controller
{
    private FileService $fileService;

    public function onConstruct()
    {
        $userRepository = new UserRepository();
        $this->fileService = new FileService($userRepository);
    }

    public function indexAction()
    {
        $response = [];
        $request = new Request();

        if ($request->isPost()) {
            $files = $this->request->getUploadedFiles('file');

            if (count($files) === 1) {
                $file = $files[0];
                $response = $this->fileService->processAndSaveFile(
                    $file,
                    $request->getPost("ime"),
                    $request->getPost("prezime"),
                    $request->getPost("mejl")
                );
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Molim unesite jedan fajl.'
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Sva polja moraju biti popunjena.'
            ];
        }

        return $this->response->setJsonContent($response);
    }
}
