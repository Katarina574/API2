<?php

namespace App\Repositories;

use Korisnik;

class UserRepository
{
    public function save(Korisnik $korisnik)
    {
        return $korisnik->save();
    }
}
