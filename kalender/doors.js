// Funktion zur Konvertierung der IDs in Zahlen
function convertIdToNumber(id) {
    const mapping = {
        one: 1,
        two: 2,
        three: 3,
        four: 4,
        five: 5,
        six: 6,
        seven: 7,
        eight: 8,
        nine: 9,
        ten: 10,
        eleven: 11,
        twelve: 12,
        thirteen: 13,
        fourteen: 14,
        fifteen: 15,
        sixteen: 16,
        seventeen: 17,
        eighteen: 18,
        nineteen: 19,
        twenty: 20,
        twentyone: 21,
        twentytwo: 22,
        twentythree: 23,
        twentyfour: 24
    };
    return mapping[id.toLowerCase()] || null;
}

// Funktion, die beim Klicken auf eine Tür ausgelöst wird
function OeffneTuer(Nummer) {
    const doorNumber = convertIdToNumber(Nummer);

    if (!doorNumber) {
        console.error("Ungültige Tür-ID:", Nummer);
        return;
    }

    $("#tuer").dialog("option", "title", "Die Gewinne hinter der " + doorNumber + ". Tür:");
    $("#tuer").dialog("open");

    const url = "show_winners.php?q=" + doorNumber;

    $.ajax({
        url: url
    }).done(function (data) {
        $("#tuer").html(data);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error("Fehler beim Laden der Daten:", textStatus, errorThrown);
        $("#tuer").html("<p>Fehler beim Laden der Daten. Bitte versuche es später erneut.</p>");
    });
}

// Initialisierung nach dem Laden des DOM
$(function () {
    // jQuery UI-Dialog initialisieren
    $("#tuer").dialog({
        autoOpen: false,
        modal: true,
        closeText: "Schließen",
        dialogClass: "dlg-no-title",
        width: 500
    });

    // Event Listener für die Türen hinzufügen
    $(".door").on("click", function () {
        OeffneTuer(this.id);
    });
});