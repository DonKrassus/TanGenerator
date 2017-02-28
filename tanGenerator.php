<?php
    header('Content-Type: text/html; charset=utf-8');
    
    // Zufallsgenerierung der TAN's
    function generiereTan($tanLength=6) {
        mt_srand();
        $charPool = "abcdefghijklmnopqrstuvwxyz";
        $charPool .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charPool .= "0123456789";
        for ($i = 0; $i < $tanLength; $i++) {
            $tan .= $charPool[mt_rand(0, strlen($charPool)-1)];
        }
        return $tan;
    }
    
    //TAN-Liste wird abgerufen und 50 neue erzeugt
    function generiereTanListe() {
        $arTanVergeben = array();
        $arTanListe = array();
        //DB-Zugangsdaten
        $servername = "";
        $username = "";
        $dbname = "";
        $passwort = "";
        // die variable $dbport wird in meinem Fall benötigt da ich in der IDE Cloud9 
        // arbeite und nur so Zugriff auf die SQL-Datenbank bekam.
        //$dbport = "";
        // in der Datenbank wird die Tabelle "tanliste" abgefragt nach folgendem Aufbau
        // "CREATE TABLE tanliste (id int NOT NULL AUTO_INCREMENT, tan varchar(6), verwendet tinyint, PRIMARY KEY(id));"
        $tblname = "tanliste";
        // Spalte "verwendet" zur späteren Nutzung
        $verwendet = null;
        // verwendung der $dbport
        $con = mysqli_connect($servername, $username, $passwort, $dbname/*, $dbport*/);
        if ($con->connect_error) {
            die("Verbindung fehlgeschlagen: " . $con->connect_error);
        }
        // Die Liste der verwendeten TAN's wird abgerufen
        $sql = "SELECT * FROM " . $tblname . ";";
        $result = $con->query($sql);
        // Überprüfung ob bereits TAN's erzeugt wurden. Falls welche gefunden wurden
        // startet die Erzeugung mit überprüfung auf Einmaligkeit
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($arTanVergeben, $row["tan"]);
            }
            $countTanDB = $result->num_rows;
            $tanVergeben = false;
            $anzTan = 0;
            $nextTan = "";
            echo "<table><thead><tr><th>PositionsNr.</th><th>TAN</th></tr></thead><tbody>";
            for ($i = 0; $anzTan < 50; $i++) {
                // eine neue TAN wird erzeugt
                $nextTan = generiereTan();
                for ($j = 0; $j < $countTanDB; $j++) {
                    // und mit den existierenden verglichen
                    if ($nextTan === $arTanVergeben[$j]) {
                        $tanVergeben = true;
                    }
                }
                // wenn die generierte TAN einmalig ist wird sie in die Datenbank geschrieben
                // und in der Tabelle angezeigt
                if ($tanvergeben == false) {
                    // der Zähler $anzTan zählt bis 50 neue TAN's erzeugt wurden
                    $anzTan++;
                    $stmt = $con->prepare("INSERT INTO " . $tblname . " (tan, verwendet) VALUES (?, ?)");
                    $stmt->bind_param("ss", $nextTan, $verwendet);
                    $stmt->execute();
                    echo "<tr><td>" . ($anzTan+$countTanDB) . "</td><td>" . $nextTan . "</td></tr>";
                }
            }
            echo "</tbody></table>";
        } else {
            // Der else - Zweig wird durchlaufen wenn die Datenbank keine Einträge beinhaltet
            echo "<table><thead><tr><th>PositionsNr.</th><th>TAN</th></tr></thead><tbody>";
            for ($i = 0; $i < 50; $i++) {
                $temp = generiereTan();
                echo "<tr><td>" . ($i+1) . "</td><td>" . $temp . "</td></tr>";
                $stmt = $con->prepare("INSERT INTO " . $tblname . " (tan, verwendet) VALUES (?, ?)");
                $stmt->bind_param("ss", $temp, $verwendet);
                $stmt->execute();
            }
            echo "</tbody></table>";
        }
    }
    
    // Aufruf der Funktionen
    generiereTanListe();
?>
