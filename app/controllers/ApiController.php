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
            // Uslov - izlistaj sve korisnike (ili fajlove) koji nisu označeni kao obrisani, jer oni koji jesu umesto null imaju trenutno vreme - soft delete
//            $korisnici = Korisnik::find([
//                'conditions' => 'deletedAt IS NULL',
//            ]);
            $korisnici = Korisnik::find();
            $sviKorisnici = [];
            foreach ($korisnici as $korisnik) {
                $sviKorisnici[] = [
                    'id' => $korisnik->id,
                    'ime' => $korisnik->ime,
                    'prezime' => $korisnik->prezime,
                    'mejl' => $korisnik->mejl,
                    'temperatura' => $korisnik->temperatura,
                ];
            }
            $response->setJsonContent($sviKorisnici);
        } else {
            $response->setJsonContent(['message' => 'Samo GET zahtevi su dozvoljeni']);
        }

        return $response;
    }

}
