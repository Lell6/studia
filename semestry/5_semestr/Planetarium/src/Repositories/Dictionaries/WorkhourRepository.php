<?php
namespace App\repository\dictionary;
use App\repository\DictionaryRepository;

class WorkhourRepository extends DictionaryRepository {
    public function __construct()
    {
        parent::__construct();
        parent::setTable("Czas_Pracy", "Id", "Okres_Godziny");
        parent::setQueries();
    }
}