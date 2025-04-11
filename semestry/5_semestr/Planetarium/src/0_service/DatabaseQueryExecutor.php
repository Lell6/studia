<?php 
namespace App\service;
use PDO;
use PDOException;
use Exception;

class DatabaseQueryExecutor extends DatabaseHandler {
    protected $query;
    private $databaseHandler;
    private $errorMessages = [
        'HY000' => 'Wystąpił ogólny błąd bazy danych. Skontaktuj się z pomocą techniczną.',
        '23000' => 'Naruszono ograniczenie relacji. Sprawdź powiązane dane.',
        '23001' => 'Naruszenie ograniczenia integralności. Nie można wstawić ani zaktualizować z powodu reguły ograniczenia.',
        '42S02' => 'Żądana tabela lub widok nie istnieje. Sprawdź zapytanie.',
        '42S22' => 'Określona kolumna nie istnieje. Sprawdź zapytanie.',
        '42000' => 'W zapytaniu SQL występuje błąd składni. Sprawdź składnię.',
        '42601' => 'Wykryto błąd składni. Sprawdź strukturę zapytania.',
        '08001' => 'Nie można nawiązać połączenia z bazą danych. Sprawdź ustawienia połączenia.',
        '08004' => 'Serwer bazy danych odrzucił połączenie. Sprawdź status serwera.',
        '08006' => 'Połączenie z bazą danych zostało utracone. Spróbuj ponownie później.',
        '28000' => 'Nieprawidłowe upoważnienie. Sprawdź poświadczenia bazy danych.',
        '22001' => 'Dane są za długie dla określonej kolumny. Dostosuj dane wejściowe.',
        '22007' => 'Nieprawidłowy format daty lub godziny. Użyj prawidłowego formatu.',
        '22012' => 'Błąd dzielenia przez zero. Sprawdź obliczenia.',
        '22003' => 'Wartość liczbowa poza zakresem. Sprawdź wartości wejściowe.',
        '22018' => 'Nieprawidłowa wartość znaku dla rzutowania. Sprawdź swoje dane.',
        '22025' => 'Nieprawidłowa sekwencja ucieczki w danych. Sprawdź swoje dane wejściowe.',
        '40001' => 'Wykryto blokadę transakcji. Spróbuj ponownie później.',
        '40002' => 'Naruszenie ograniczenia integralności transakcji. Sprawdź operację.',
        '40P01' => 'Brak blokady transakcji. Spróbuj ponownie wykonać transakcję.',
        '53000' => 'Brak zasobów. Serwer bazy danych nie może przetworzyć żądania.',
        '53100' => 'Dysk jest pełny. Nie można przetworzyć żądania bazy danych.',
        '53200' => 'Brak pamięci. Spróbuj ponownie później.',
        '42P01' => 'Brak uprawnień do dostępu do tego zasobu. Sprawdź swoje uprawnienia.',
        '42501' => 'Odmowa uprawnień. Nie masz dostępu do wykonania tej czynności.',
        'default' => 'Wystąpił nieoczekiwany błąd bazy danych. Skontaktuj się z pomocą techniczną.'
    ];

    public function __construct()
    {
        $this->databaseHandler = new DatabaseHandler();
        $this->databaseHandler->establishConnection('admin');
    }

    public function isTransaction() {
        return "In transaction: " . (parent::$pdo->inTransaction() ? 'Yes' : 'No');
    }

    public function startTransaction() {
        parent::$pdo->beginTransaction();
    }

    public function rollBackTransaction() {
        if (parent::$pdo->inTransaction()) {
            parent::$pdo->rollBack();
        }
    }

    public function commitTransaction() {
        if (!parent::$pdo->inTransaction()) {
            throw new Exception("No active transaction to commit");
        }
        parent::$pdo->commit();
    }

    private function getExceptionMessage($errorCode) {
        if (array_key_exists($errorCode, $this->errorMessages)) {
            return $this->errorMessages[$errorCode];
        } else {
            return $this->errorMessages['default'];
        }
    }

    public function executePreparedQuery($query, $parametres) {
        /*$queryType = strtoupper(strtok(trim($query), " "));
        $isDataModification = in_array($queryType, ['INSERT', 'UPDATE', 'DELETE']);

        if (!$this->isAdminUser() && $isDataModification) {
            throw new Exception("Unauthorized modification");
        }*/

        try {
            $this->query = $query;
            $this->query = parent::$pdo->prepare($this->query);      

            foreach ($parametres as $key => $value) {
                if ($key == "content") {
                    $this->query->bindParam(":$key", $value, PDO::PARAM_LOB);
                }
            }

            $this->query->execute($parametres);
            return [
                'success' => true,
                'message' => 'yaaay'
            ];
        }
        catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $this->getExceptionMessage($exception->getCode())
            ];
        }
        catch (PDOException $exception) {
            return [
                'success' => false,
                'message' => $this->getExceptionMessage($exception->getCode())
            ];
        }
    }

    public function getQueryResult() {
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }    
    public function getQueryResultMultiple() {
        return $this->query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastInserted() {
        return parent::$pdo->lastInsertId();
    }

    public function isAdminUser() {
        return $this->databaseHandler->getIsAdmin();
    }
}