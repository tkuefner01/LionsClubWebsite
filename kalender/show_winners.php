<?php
include("conf.php");

$q = intval($_GET['q']);
$con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);

if (!$con) {
    die('Verbindung zur Datenbank fehlgeschlagen: ' . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");
mysqli_select_db($con, $db['database']);

// Funktion zum Erstellen einer Tabelle aus den Ergebnissen
function renderTable($result, $headers) {
    echo "<table width=\"450\">";
    echo "<tr>";
    foreach ($headers as $header) {
        echo "<th>$header</th>";
    }
    echo "</tr>";

    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        foreach ($row as $key => $value) {
            if (!is_int($key)) { // Vermeidet das Ausgeben von Array-Schlüssel
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";
}

if ($q == 1919) {
    // Für den speziellen Fall 1919
    $sql = "SELECT Datum, Nummer, Gewinn, Sponsor FROM kalender ORDER BY Tag";
    $result = mysqli_query($con, $sql);

    if ($result) {
        renderTable($result, ['Datum', 'Losnummer', 'Gewinn', 'Sponsor']);
    } else {
        echo "Daten konnten nicht abgerufen werden.";
    }
} elseif ($q == 0) {
    // Wenn keine Ziehung für dieses Datum vorhanden ist
    echo "<table width=\"450\"><tr><td>Für dieses Datum liegt keine Ziehung vor</td></tr></table>";
} else {
    $date = sprintf('2024-12-%02d', $q);
    
    // SQL-Abfragen für das gewählte Datum
    $sql = "SELECT Nummer, Gewinn, Sponsor FROM kalender WHERE datum = ? AND datum <= CURDATE() ORDER BY Nummer";
    $sql_ohne = "SELECT Gewinn, Sponsor FROM kalender WHERE datum = ? ORDER BY Gewinn";

    // Vorbereiten und Ausführen der Abfragen
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $date);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 0) {
            // Falls keine Ziehungen vorhanden sind
            echo "<table width=\"450\"><tr><td>Für diesen Tag liegen noch keine Ziehungsdaten vor!</td></tr></table>";
            echo "<table width=\"450\"><tr><td>Aber das sind die Gewinne für diesen Tag:</td></tr></table>";
            
            if ($stmt_ohne = mysqli_prepare($con, $sql_ohne)) {
                mysqli_stmt_bind_param($stmt_ohne, "s", $date);
                mysqli_stmt_execute($stmt_ohne);
                $result_ohne = mysqli_stmt_get_result($stmt_ohne);
                renderTable($result_ohne, ['Gewinn', 'Sponsor']);
            }
        } else {
            renderTable($result, ['Losnummer', 'Gewinn', 'Sponsor']);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Fehler bei der Datenbankabfrage.";
    }
}

mysqli_close($con);
?>
