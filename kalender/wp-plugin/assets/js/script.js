jQuery(document).ready(function ($) {
    function ZeigeGewinn(Nummer) {
        $.ajax({
            url: ajaxurl, // WordPress AJAX-URL
            type: 'GET',
            data: {
                action: 'lionskalender_abfrage',
                n: Nummer
            },
            success: function (response) {
                if (response.success) {
                    let html = `
                        <table class="lionskalender-table">
                            <thead>
                                <tr>
                                    <th>Losnummer</th>
                                    <th>Datum</th>
                                    <th>Gewinn</th>
                                    <th>Sponsor</th>
                                </tr>
                            </thead>
                            <tbody>`;
                    response.data.forEach(function (item) {
                        html += `
                            <tr>
                                <td>${item.nummer}</td>
                                <td>${item.datum}</td>
                                <td>${item.gewinn}</td>
                                <td>${item.sponsor}</td>
                            </tr>`;
                    });
                    html += `
                            </tbody>
                        </table>
                        <div class="success-message">Herzlichen Glückwunsch! Sie haben gewonnen.</div>`;
                    $('#zeige').html(html);
                } else {
                    $('#zeige').html(`
                        <div class="error-message">
                            ${response.data.message}
                        </div>`);
                }
            },
            error: function () {
                $('#zeige').html(`
                    <div class="error-message">
                        Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.
                    </div>`);
            }
        });
    }

    $('#Abfrage').click(function () {
        const nummer = $('#num').val();
        if (nummer) {
            ZeigeGewinn(nummer);
        } else {
            $('#zeige').html(`
                <div class="error-message">
                    Bitte geben Sie eine gültige Losnummer ein.
                </div>`);
        }
    });
});
