<?php

use Phalcon\Mvc\Controller;

class TestController extends Controller
{
    public function indexAction()
    {
        $request = $this->request;
        $response = $this->response;

        if ($request->isGet()) {
            $data = "Test";
            $response->setJsonContent($data);
        } else {
            $response->setJsonContent(['message' => 'Samo GET zahtevi su dozvoljeni']);
        }

        return $response;
    }
}
