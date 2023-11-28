<?php

use App\Services\FileService;
use App\Services\UserService;
use Phalcon\Http\Request;
use \Phalcon\Mvc\Controller;
class UploadController extends Controller
{
    public function indexAction()
    {
        $us = new UserService();
        $this->fileService = new FileService($us);
        $this->userService = new UserService();

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
