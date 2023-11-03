<?php
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

class ApiController extends Controller
{
    public function indexAction()
    {
        $response = new Response();
        $request = new Request();

        if ($request->isGet()) {
            $data = "Test";
            $response->setJsonContent($data);
        } else {
            $response->setJsonContent(['message' => 'Samo GET zahtevi su dozvoljeni']);
        }

        return $response;
    }
}
