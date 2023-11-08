<?php
use Phalcon\Mvc\Model;

class Korisnik extends Model
{
    public $id;
    public $ime;
    public $prezime;
    public $mejl;
    public $temperatura;
    public $file;
    public $deletedAt;

}
