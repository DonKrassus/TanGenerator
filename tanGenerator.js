var btnTanErzeugen = document.getElementById("erzeugeTan");
btnTanErzeugen.addEventListener('click', sendRequest, false);

// Bei Klick auf den Button wird eine Verbindung mit der tanGenerator.php Datei erstellt
var request = new XMLHttpRequest();
function sendRequest() {
    // Request erzeugen 
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest();
    }
    // Überprügfen ob der Request erzeugt wurde
    if (!request) {
        alert("Kann keine XMLHTTP-Instanz erzeugen");
        return false;
    } else {
        var url ="tanGenerator.php";
        // Request öffnen
        request.open('post', url, true);
        // Request senden
        request.send(null);
        // Request auswerten
        request.onreadystatechange = interpretRequest;
    }
}

// Funktion erhält die Daten aus der tanGenerator.php Datei und stellt sie im Browser dar
function interpretRequest() {
    switch (request.readyState) {
        case 4:
            if (request.status != 200) {
                alert("Der Request wurde abgeschlossen");
            } else {
                var content = request.responseText;
                // den Inhalt des Requests in das <div> schreiben
                document.getElementById('tanListe').innerHTML = content;
            }
            break;
        default:
            break;
    }
}