<?php
namespace App\repository;
use App\service\DatabaseQueryExecutor;
use PDOException;
use Exception;

class DictionaryRepository {
    private $queryExecutor;
    private $tableName;
    private $idColumnName;
    private $valueColumnName;

    private $querySelectAll;
    private $querySelectById;
    private $querySelectByValue;
    private $queryInsert;
    private $queryInsertWithDefault;
    private $queryUpdateById;
    private $queryUpdateByIdWithDefault;
    private $queryUnsetDefault;
    private $queryDeleteById;

    public function __construct()
    {
        $this->queryExecutor = new DatabaseQueryExecutor();
    }

    protected function setTable($tableName, $idName = "Id", $valueName = "Nazwa") {        
        $this->tableName = $tableName;
        $this->idColumnName = $idName;
        $this->valueColumnName = $valueName;
    }

    protected function setQueries() {
        $this->querySelectAll = "SELECT * FROM " . $this->tableName;
        $this->querySelectById = "SELECT * FROM " . $this->tableName . " WHERE " . $this->idColumnName . " = :id";
        $this->querySelectByValue = "SELECT * FROM " . $this->tableName . " WHERE " . $this->valueColumnName . " = :value";
        $this->queryUpdateById = "UPDATE " . $this->tableName . " SET " . $this->valueColumnName . " = :value WHERE " . $this->idColumnName . " = :id";
        $this->queryUpdateByIdWithDefault = "UPDATE " . $this->tableName . " SET " . $this->valueColumnName . " = :value, Czy_Standardowa = :default WHERE " . $this->idColumnName . " = :id";
        $this->queryUnsetDefault = "UPDATE " . $this->tableName . " SET Czy_Standardowa = 0";
        $this->queryDeleteById = "DELETE FROM " . $this->tableName . " WHERE " . $this->idColumnName . " = :id";
        $this->queryInsert = "INSERT INTO " . $this->tableName . "(" . $this->valueColumnName . ")" . "VALUES (:value)";  
        $this->queryInsertWithDefault = "INSERT INTO " . $this->tableName . "(" . $this->valueColumnName . ", Czy_Standardowa)" . "VALUES (:value, :default)";        
    }

    public function getAllRecords() {
        $result = $this->queryExecutor->executePreparedQuery($this->querySelectAll, []);

        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }
        return $result;
    }

    public function getRecordById($id) {
        $result = $this->queryExecutor->executePreparedQuery($this->querySelectById, [
            'id' => $id
        ]);

        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] != null) {
            return [
                'success' => false,
                'message' => "Rekord z takin ID nie istnieje"
            ];
        }

        return $result;
    }

    public function getRecordByValue($value) {
        $result = $this->queryExecutor->executePreparedQuery($this->querySelectByValue, [
            'value' => $value
        ]);
        
        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] != null) {
            return [
                'success' => false,
                'message' => "Rekord z taką wartością już istnieje"
            ];
        }

        return $result;
    }

    public function insertNewRecord($value) {
        $result = $this->getRecordByValue($value);
        if (!$result['success']) {
            return $result;
        }

        return $this->queryExecutor->executePreparedQuery($this->queryInsert, [
            'value' => $value
        ]);
    }

    public function insertNewRecordWithDefault($value, $isDefault) {
        $result = $this->getRecordByValue($value);
        if (!$result['success']) {
            return $result;
        }
        
        return $this->queryExecutor->executePreparedQuery($this->queryInsertWithDefault, [
            'value' => $value,
            'default' => $isDefault
        ]);
    }

    public function unsetDefaultValue() {
        return $this->queryExecutor->executePreparedQuery($this->queryUnsetDefault, []);
    }

    public function updateRecordWithDefault($id, $value, $isDefault) {
        $result = $this->getRecordById($id);
        if (!$result['success']) {
            return $result;
        }

        $result = $this->getRecordByValue($value);
        if (!$result['success']) {
            return $result;
        }

        if ($isDefault == 1) {
            $this->unsetDefaultValue();
        }

        return $this->queryExecutor->executePreparedQuery($this->queryUpdateByIdWithDefault, [
            'id' => $id,
            'value' => $value,
            'default' => $isDefault
        ]);
    }

    public function updateRecord($id, $value) {
        $result = $this->getRecordById($id);
        if (!$result['success']) {
            return $result;
        }

        $result = $this->getRecordByValue($value);
        if (!$result['success']) {
            return $result;
        }

        return $this->queryExecutor->executePreparedQuery($this->queryUpdateById, [
            'id' => $id,
            'value' => $value
        ]);
    }

    public function deleteRecord($id) {
        $result = $this->getRecordById($id);
        if (!$result['success']) {
            return $result;
        }

        return $this->queryExecutor->executePreparedQuery($this->queryDeleteById, [
            'id' => $id
        ]);
    }
}