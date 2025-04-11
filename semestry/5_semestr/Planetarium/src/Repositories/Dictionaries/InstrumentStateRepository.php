<?php
namespace App\repository\dictionary;
use App\repository\DictionaryRepository;

class InstrumentStateRepository extends DictionaryRepository {
    public function __construct()
    {
        parent::__construct();
        parent::setTable("Stan_Sprzętu");
        parent::setQueries();
    }
}