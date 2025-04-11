<?php
namespace App\repository\dictionary;
use App\repository\DictionaryRepository;

class RoomStateRepository extends DictionaryRepository {
    public function __construct()
    {
        parent::__construct();
        parent::setTable("Stan_Sali");
        parent::setQueries();
    }
}