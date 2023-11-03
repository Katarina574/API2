alert("Test da vidimo da li radi.");

$(document).ready(function() {
    $('#loadData').click(function() {
        var URL = 'http://localhost:8086/api';

        $.ajax({
            url: URL,
            method: 'GET',
            success: function(data) {
                $('#rezultat').text(JSON.stringify(data, null, 2));
            },
            error: function(error) {
                $('#rezultat').text('Greska prilikom obrade podataka.');
            }
        });
    });
});

$(document).ready(function() {
    $('#posalji').click(function(event) {
        var URL = 'http://localhost:8086/upload';
        var ime = $('#ime').val(); //preuzmi vrednost iz polja za unos imena

        $.ajax({
            url: URL,
            method: 'POST',
            data: { ime: ime },
            success: function(data) {
                $('#rezultat').text('Uspesno ste poslali ime: ' + data);
            },
            error: function(error) {
                $('#rezultat').text('Greska prilikom obrade podataka.');
            }
        });
    });
});