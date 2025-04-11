<?php
namespace App\repository\dictionary;
use App\repository\DictionaryRepository;

class PrivilegesRepository extends DictionaryRepository {
    public function __construct()
    {
        parent::__construct();
        parent::setTable("Przywileje");
        parent::setQueries();
    }
}