<?php
/*
Plugin Name: Lionskalender Plugin
Description: Gibt Daten aus dem aktuellen Lionskalender aus
Version: 1.3
Author: Thomas
*/

// Funktion zum Laden der CSS-Datei
function lionskalender_enqueue_styles() {
    // Registriere und lade die CSS-Datei
    wp_enqueue_style(
        'lionskalender-styles', // Handle der CSS-Datei
        plugin_dir_url(__FILE__) . 'assets/css/style.css', // Pfad zur CSS-Datei
        array(), // Abhängigkeiten
        '1.0', // Version
        'all' // Medienart
    );
}
add_action('wp_enqueue_scripts', 'lionskalender_enqueue_styles');

// Funktion für den Shortcode
function gewinneheute_shortcode() {
    // Datenbankkonfiguration
    $db = [
        'host' => "127.0.0.1",
        'user' => "u807590204_kalender",
        'password' => "Lions123765",
        'database' => "u807590204_lionskalender"
    ];

    // Verbindung zur Datenbank herstellen
    $con = new mysqli($db['host'], $db['user'], $db['password'], $db['database']);
    
    if ($con->connect_error) {
        die("Datenbankverbindung fehlgeschlagen: " . $con->connect_error);
    }
    
    $con->set_charset("utf8");

    // Aktuelles Datum und Zeitraum definieren
    $currentDate = time();
    $startDate = strtotime('2024-12-01');
    $endDate = strtotime('2024-12-24');

    // Header für die Ausgabe
    echo '<div class="lionskalender-header">Gewinnzahlen für den ' . date('d.m.Y') . '</div>';
    echo '<div class="lionskalender-subheader">Folgende Kalendernummern wurden heute gezogen und haben den angegebenen Preis gewonnen!</div>';
    
    if ($currentDate >= $startDate && $currentDate <= $endDate) {
        // SQL-Statement vorbereiten
        $sql = "SELECT Datum, Nummer, Gewinn, Sponsor FROM kalender WHERE Datum = CURDATE() ORDER BY Nummer";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        // Ergebnisse ausgeben
        echo '<table class="lionskalender-table">';
        echo '<tr><th>Losnummer</th><th>Gewinn</th><th>Sponsor</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['Nummer'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['Gewinn'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['Sponsor'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        $stmt->close();
    } else {
        echo '<div class="lionskalender-subheader">Heute ist <strong>kein Ziehungstag</strong>.</div>';
    }

    // Verbindung schließen
    $con->close();
}

// Shortcode registrieren
add_shortcode('gewinneheute', 'gewinneheute_shortcode');

// AJAX-Funktion zur Gewinnabfrage
function lionskalender_ajax_handler() {
    // Prüfen, ob die Nummer übergeben wurde
    if (!isset($_GET['n']) || empty($_GET['n'])) {
        wp_send_json_error(['message' => 'Keine Losnummer übermittelt.']);
        wp_die();
    }

    $nummer = intval($_GET['n']);

    // Datenbankverbindung konfigurieren
    $db = [
        'host' => "127.0.0.1",
        'user' => "u807590204_kalender",
        'password' => "Lions123765",
        'database' => "u807590204_lionskalender"
    ];

    $con = new mysqli($db['host'], $db['user'], $db['password'], $db['database']);
    if ($con->connect_error) {
        wp_send_json_error(['message' => 'Datenbankverbindung fehlgeschlagen.']);
        wp_die();
    }
    $con->set_charset("utf8");

    $sql = "SELECT Datum, Nummer, Gewinn, Sponsor FROM kalender WHERE Nummer = ? AND Datum <= CURDATE() ORDER BY Datum";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $nummer);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $con->close();
        wp_send_json_error(['message' => "Die Nummer {$nummer} wurde leider nicht gezogen."]);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'nummer' => $row['Nummer'],
            'datum' => date("d.m.Y", strtotime($row['Datum'])),
            'gewinn' => $row['Gewinn'],
            'sponsor' => $row['Sponsor']
        ];
    }

    $stmt->close();
    $con->close();

    wp_send_json_success($data);
    wp_die();
}

// AJAX-Endpunkt registrieren
add_action('wp_ajax_nopriv_lionskalender_abfrage', 'lionskalender_ajax_handler'); // Für nicht eingeloggte Nutzer
add_action('wp_ajax_lionskalender_abfrage', 'lionskalender_ajax_handler');       // Für eingeloggte Nutzer

function lionskalender_enqueue_scripts() {
    wp_enqueue_script('lionskalender-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', ['jquery'], '1.0', true);
    wp_localize_script('lionskalender-script', 'ajaxurl', admin_url('admin-ajax.php')); // AJAX-URL bereitstellen
}
add_action('wp_enqueue_scripts', 'lionskalender_enqueue_scripts');

function lionskalender_form_shortcode() {
    ob_start();
    ?>
    <table class="lionskalender-input-table">
        <thead>
            <tr><th>Bitte tragen Sie Ihre Losnummer ein</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <input class="ui-corner-all" type="text" name="nummer" id="num">
                    <button class="ui-button-text" id="Abfrage">Los!</button>
                </td>
            </tr>
        </tbody>
    </table>
    <div id="zeige"></div>
    <?php
    return ob_get_clean();
}
add_shortcode('lionskalender_form', 'lionskalender_form_shortcode');
