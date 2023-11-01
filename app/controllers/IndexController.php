<?php
//declare(strict_types=1);

use http\Client\Request;
use http\Client\Response;

class IndexController extends ControllerBase
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

