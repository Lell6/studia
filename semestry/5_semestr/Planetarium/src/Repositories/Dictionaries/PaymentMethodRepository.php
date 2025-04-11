<?php
namespace App\repository\dictionary;
use App\repository\DictionaryRepository;

class PaymentMethodRepository extends DictionaryRepository {
    public function __construct()
    {
        parent::__construct();
        parent::setTable("Metoda_Płatności");
        parent::setQueries();
    }
}