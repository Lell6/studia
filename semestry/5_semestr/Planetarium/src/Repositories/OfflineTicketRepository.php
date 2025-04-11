<?php
namespace App\repository;

class OfflineTicketRepository extends TicketRepository {
    protected function initializeQueries() {
        $this->queryAddNewTicket = "INSERT INTO 
                                        Bilet_Stacjonarnie (
                                            Data_Czas_Utworzenia,
                                            Metoda_Płatności, 
                                            Status,
                                            Cena)
                                      VALUES(
                                        :date,
                                        :payment,
                                        :status,
                                        :price)";

        $this->querySelectTicketById = "SELECT 
                                            Bilet_Stacjonarnie.Id, 
                                            Bilet_Stacjonarnie.Data_Czas_Utworzenia, 
                                            Metoda_Płatności.Nazwa AS Metoda_Płatności,
                                            Wykłady_Na_Bilecie.Id AS Wykład_Id,
                                            Wykład.Tytuł,
                                            Wykład.Opis,
                                            Wykład.Cena_Uczęstnictwa AS Cena_Wykładu,
                                            Bilet_Stacjonarnie.Cena
                                        FROM
                                            Bilet_Stacjonarnie
                                        JOIN
                                            Wykłady_Na_Bilecie
                                            ON Bilet_Stacjonarnie.Id = Wykłady_Na_Bilecie.Bilet_Id
                                        JOIN
                                            Metoda_Płatności
                                            ON Bilet_Online.Metoda_Płatności = Metoda_Płatności.Id
                                        JOIN
                                            Wykład
                                            ON Wykłady_Na_Bilecie.Wykład_Id = Wykład.Id
                                        WHERE
                                            Bilet_Stacjonarnie.Id = :id";
        $this->querySelectTicketPrice = "SELECT 
                                            Bilet_Stacjonarnie.Id,
                                            Bilet_Stacjonarnie.Cena
                                        FROM 
                                            Bilet_Stacjonarnie
                                        WHERE
                                            Bilet_Stacjonarnie.Id = :id";

        $this->querySelectAllTickets = "SELECT 
                                            Bilet_Stacjonarnie.Id, 
                                            Bilet_Stacjonarnie.Data_Czas_Utworzenia, 
                                            Metoda_Płatności.Nazwa AS Metoda_Płatności,
                                            Bilet_Stacjonarnie.Status, 
                                            Wykłady_Na_Bilecie.Id AS Wykład_Id,
                                            Wykłady_Na_Bilecie.Status AS Status_Wykładu,
                                            Wykład.Tytuł,
                                            Wykład.Opis,
                                            Wykład.Cena_Uczęstnictwa AS Cena_Wykładu,                                            
                                            Bilet_Stacjonarnie.Cena
                                        FROM
                                            Bilet_Stacjonarnie
                                        JOIN
                                            Wykłady_Na_Bilecie
                                            ON Bilet_Stacjonarnie.Id = Wykłady_Na_Bilecie.Bilet_Offline_Id
                                        JOIN
                                            Metoda_Płatności
                                            ON Bilet_Stacjonarnie.Metoda_Płatności = Metoda_Płatności.Id
                                        JOIN
                                            Wykład
                                            ON Wykłady_Na_Bilecie.Wykład_Id = Wykład.Id";

        $this->queryUpdateTicketStatus = "UPDATE Bilet_Stacjonarnie SET Status = :newStatus WHERE Bilet_Stacjonarnie.Id = :id";
        $this->queryUpdateTicketPayment = "UPDATE Bilet_Stacjonarnie SET Metoda_Płatności = :payment WHERE Bilet_Stacjonarnie.Id = :id";
        $this->queryUpdateTicketPrice = "UPDATE Bilet_Stacjonarnie SET Cena = :price WHERE Bilet_Stacjonarnie.Id = :id";
        $this->queryUpdateLectureTicketStatus = "UPDATE Wykłady_Na_Bilecie SET Status = :status WHERE Id = :id";  

        $this->queryAddLectureToTicket = "INSERT INTO Wykłady_Na_Bilecie (Wykład_id, Bilet_Offline_Id, Typ_Biletu, Status)
                                      VALUES (:lectureId, :ticketId, :type, :status)";
        $this->queryDeleteLectureFromTicket = "DELETE FROM Wykłady_na_Bilecie 
                                                WHERE Wykłady_na_Bilecie.Id = :lectureTicketId";
        $this->querySelectLecturesFromTicket = "SELECT Wykłady_Na_Bilecie.Wykład_Id, Wykład.Zajętość, Wykład.Cena_Uczęstnictwa AS Cena
                                                FROM Wykłady_Na_Bilecie
                                                INNER JOIN Wykład ON Wykłady_Na_Bilecie.Wykład_Id = Wykład.Id
                                                WHERE Wykłady_Na_Bilecie.Bilet_Offline_Id = :ticketId
                                                AND Wykłady_Na_Bilecie.Typ_Biletu = :type"; 
        $this->querySelectOneLectureFromTicket = "SELECT Wykłady_Na_Bilecie.Id, Wykłady_Na_Bilecie.Wykład_Id, Wykład.Zajętość
                                                    FROM Wykłady_Na_Bilecie
                                                    INNER JOIN Wykład ON Wykłady_Na_Bilecie.Wykład_Id = Wykład.Id
                                                    WHERE Wykłady_Na_Bilecie.Id = :lectureTicketId";
    }

    public function addNewTicket($date, $status, $method, $price, $sessionHandler) {
        return $this->queryExecutor->executePreparedQuery($this->queryAddNewTicket, [
            'date' => $date,
            'payment' => $method,
            'status' => $status,
            'price' => $price
        ]);
    }
}