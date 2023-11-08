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
            //nadji sve korisnike koji nisu oznaceni kao obrisani, jer oni koji jesu umesto null imaju trenutno vreme - soft delete
            $korisnici = Korisnik::find([
                'conditions' => 'deletedAt IS NULL',
            ]);
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

    public function deleteAction($id)
    {
        $response = new Response();
        $request = new Request();

        if ($request->isPut()) {
            //nadji fajl po id-u
            $korisnik = Korisnik::findFirst($id);

            if ($korisnik) {
                //posstavi deletedAt na trenutno vreme za "meko" brisanje
                $korisnik->deletedAt = date('Y-m-d H:i:s');
                $korisnik->save();

                $response->setJsonContent(['message' => 'Fajl uspešno obrisan']);
            } else {
                $response->setJsonContent(['message' => 'Fajl nije pronađen']);
            }
        } else {
            $response->setJsonContent(['message' => 'Samo DELETE zahtevi su dozvoljeni']);
        }

        return $response;
    }

}
