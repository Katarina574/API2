<?php

namespace App\Services;
use Korisnik;

class UserService
{
    public function saveUser($ime, $prezime, $mejl, $tempC, $fileName, $filePath)
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