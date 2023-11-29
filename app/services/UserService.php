<?php

namespace App\Services;

use Korisnik;
use App\Repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function saveUser($ime, $prezime, $mejl, $tempC, $fileName, $filePath)
    {
        $korisnik = new Korisnik();
        $korisnik->ime = $ime;
        $korisnik->prezime = $prezime;
        $korisnik->mejl = $mejl;
        $korisnik->temperatura = $tempC;
        $korisnik->file = $filePath;

        return $this->userRepository->save($korisnik);
    }
}
