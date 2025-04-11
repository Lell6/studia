<?php
namespace App\repository;

class OnlineTicketRepository extends TicketRepository {

    protected function initializeQueries() {
        $this->queryAddNewTicket = "INSERT INTO 
                                        Bilet_Online (
                                            Id_Kupującego, 
                                            Imię_Kupującego, 
                                            Nazwisko_Kupującego, 
                                            Email_Kupującego, 
                                            Data_Czas_Utworzenia,
                                            Metoda_Płatności, 
                                            Status,
                                            Cena)
                                      VALUES(
                                        :id,
                                        :name,
                                        :surname,
                                        :email,
                                        :date,
                                        :payment,
                                        :status,
                                        :price)";

        $this->querySelectTicketById = "SELECT 
                                            Bilet_Online.Id, 
                                            Bilet_Online.Id_Kupującego,
                                            Bilet_Online.Data_Czas_Utworzenia, 
                                            Bilet_Online.Metoda_Płatności,
                                            Bilet_Online.Status,
                                            Wykłady_Na_Bilecie.Id AS Wykład_Id,
                                            Wykład.Tytuł,
                                            Wykład.Opis,
                                            Wykład.Cena_Uczęstnictwa AS Cena_Wykładu,
                                            Bilet_Online.Cena
                                        FROM
                                            Bilet_Online
                                        JOIN
                                            Wykłady_Na_Bilecie
                                            ON Bilet_Online.Id = Wykłady_Na_Bilecie.Bilet_Online_Id
                                        JOIN
                                            Wykład
                                            ON Wykłady_Na_Bilecie.Wykład_Id = Wykład.Id
                                        WHERE
                                            Bilet_Online.Id = :id";
        $this->querySelectTicketPrice = "SELECT 
                                            Bilet_Online.Id,
                                            Bilet_Online.Cena
                                        FROM 
                                            Bilet_Online
                                        WHERE
                                            Bilet_Online.Id = :id";

        $this->querySelectAllClientTickets = "SELECT 
                                            Bilet_Online.Id, 
                                            Bilet_Online.Data_Czas_Utworzenia, 
                                            Metoda_Płatności.Nazwa AS Metoda_Płatności,
                                            Bilet_Online.Status,
                                            Bilet_Online.Id_Kupującego,
                                            Bilet_Online.Imię_Kupującego,
                                            Bilet_Online.Nazwisko_Kupującego,
                                            Bilet_Online.Email_Kupującego,
                                            Wykłady_Na_Bilecie.Id AS Wykład_Id,
                                            Wykłady_Na_Bilecie.Status AS Status_Wykładu,
                                            Wykład.Tytuł,
                                            Wykład.Opis,
                                            Wykład.Cena_Uczęstnictwa AS Cena_Wykładu,
                                            Bilet_Online.Cena
                                        FROM
                                            Bilet_Online
                                        JOIN
                                            Wykłady_Na_Bilecie
                                            ON Bilet_Online.Id = Wykłady_Na_Bilecie.Bilet_Online_Id
                                        JOIN
                                            Metoda_Płatności
                                            ON Bilet_Online.Metoda_Płatności = Metoda_Płatności.Id
                                        JOIN
                                            Wykład
                                            ON Wykłady_Na_Bilecie.Wykład_Id = Wykład.Id
                                        WHERE 
                                            Bilet_Online.Id_Kupującego = :clientId";

        $this->querySelectAllTickets = "SELECT 
                                            Bilet_Online.Id, 
                                            Bilet_Online.Data_Czas_Utworzenia, 
                                            Metoda_Płatności.Nazwa AS Metoda_Płatności,
                                            Bilet_Online.Status,
                                            Bilet_Online.Id_Kupującego,
                                            Bilet_Online.Imię_Kupującego,
                                            Bilet_Online.Nazwisko_Kupującego,
                                            Bilet_Online.Email_Kupującego,
                                            Wykłady_Na_Bilecie.Id AS Wykład_Id,
                                            Wykłady_Na_Bilecie.Status AS Status_Wykładu,
                                            Wykład.Tytuł,
                                            Wykład.Opis,
                                            Wykład.Cena_Uczęstnictwa AS Cena_Wykładu,
                                            Bilet_Online.Cena
                                        FROM
                                            Bilet_Online
                                        JOIN
                                            Wykłady_Na_Bilecie
                                            ON Bilet_Online.Id = Wykłady_Na_Bilecie.Bilet_Online_Id
                                        JOIN
                                            Metoda_Płatności
                                            ON Bilet_Online.Metoda_Płatności = Metoda_Płatności.Id
                                        JOIN
                                            Wykład
                                            ON Wykłady_Na_Bilecie.Wykład_Id = Wykład.Id";

        $this->queryUpdateTicketStatus = "UPDATE Bilet_Online SET Status = :newStatus WHERE Bilet_Online.Id = :id";
        $this->queryUpdateTicketPayment = "UPDATE Bilet_Online SET Metoda_Płatności = :payment WHERE Bilet_Online.Id = :id";
        $this->queryUpdateTicketPrice = "UPDATE Bilet_Online SET Cena = :price WHERE Bilet_Online.Id = :id";

        $this->queryAddLectureToTicket = "INSERT INTO Wykłady_Na_Bilecie (Wykład_id, Bilet_Online_Id, Typ_Biletu, Status)
                                      VALUES (:lectureId, :ticketId, :type, :status)";
        $this->queryDeleteLectureFromTicket = "DELETE FROM Wykłady_na_Bilecie 
                                                WHERE Wykłady_na_Bilecie.Id = :lectureTicketId";
        $this->queryUpdateLectureTicketStatus = "UPDATE Wykłady_Na_Bilecie SET Status = :status WHERE Id = :id";                                                
        $this->querySelectLecturesFromTicket = "SELECT Wykłady_Na_Bilecie.Id, Wykłady_Na_Bilecie.Wykład_Id, Wykłady_Na_Bilecie.Status, Wykład.Zajętość, Wykład.Cena_Uczęstnictwa AS Cena
                                                FROM Wykłady_Na_Bilecie
                                                INNER JOIN Wykład ON Wykłady_Na_Bilecie.Wykład_Id = Wykład.Id
                                                WHERE Wykłady_Na_Bilecie.Bilet_Online_Id = :ticketId
                                                AND Wykłady_Na_Bilecie.Typ_Biletu = :type";
        $this->querySelectOneLectureFromTicket = "SELECT Wykłady_Na_Bilecie.Id, Bilet_Online_Id, Wykłady_Na_Bilecie.Wykład_Id, Wykłady_Na_Bilecie.Status, Wykład.Zajętość
                                                    FROM Wykłady_Na_Bilecie
                                                    INNER JOIN Wykład ON Wykłady_Na_Bilecie.Wykład_Id = Wykład.Id
                                                    WHERE Wykłady_Na_Bilecie.Id = :lectureTicketId";
        $this->querySelectFromCartByClientId = "SELECT 
                                                    Bilet_Online.Id, 
                                                    Bilet_Online.Data_Czas_Utworzenia, 
                                                    Bilet_Online.Metoda_Płatności, 
                                                    Wykłady_Na_Bilecie.Id AS Wykład_Id,
                                                    Wykłady_Na_Bilecie.Status,
                                                    Wykład.Tytuł,
                                                    Wykład.Opis,
                                                    Wykład.Cena_Uczęstnictwa AS Cena_Wykładu
                                                FROM
                                                    Bilet_Online
                                                JOIN
                                                    Wykłady_Na_Bilecie
                                                    ON Bilet_Online.Id = Wykłady_Na_Bilecie.Bilet_Online_Id
                                                JOIN
                                                    Wykład
                                                    ON Wykłady_Na_Bilecie.Wykład_Id = Wykład.Id
                                                WHERE
                                                    Bilet_Online.Status = :status
                                                AND 
                                                    Bilet_Online.Id_Kupującego = :clientId";
    }

    public function addNewTicket($date, $status, $method, $price, $sessionHandler) {
        $clientRepo = new ClientRepository();
        $userId = $sessionHandler->getUserValueByKey('userId');
        $result = $clientRepo->getClientByUserId($userId);

        if (!$result['success']) {
            return $result;
        }
        
        $clientData = $result['record'];
        return $this->queryExecutor->executePreparedQuery($this->queryAddNewTicket, [
            'id' => $clientData['Id'],
            'name' => $clientData['Imię'],
            'surname' => $clientData['Nazwisko'],
            'email' => $clientData['Email'],
            'date' => $date,
            'payment' => $method,
            'status' => $status,
            'price' => $price
        ]);
    }

    public function getAllClientTickets($clientId) {
        $result = $this->queryExecutor->executePreparedQuery($this->querySelectAllClientTickets, [
            'clientId' => $clientId
        ]);
        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }
}