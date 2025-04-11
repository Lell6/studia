<?php
namespace App\repository\dictionary;
use App\repository\DictionaryRepository;

class DurationRepository extends DictionaryRepository {
    public function __construct()
    {
        parent::__construct();
        parent::setTable("Czas_Trwania", "Id", "Długość");
        parent::setQueries();
    }
}