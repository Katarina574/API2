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
        var ime = $('#ime').val(); // Preuzmi vrednost iz polja za unos imena
        var prezime = $('#prezime').val(); // Preuzmi vrednost iz polja za unos prezimena
        var mejl = $('#mejl').val(); // Preuzmi vrednost iz polja za unos mejla
        var file = $('#file').val(); //uzimamo fajl iz forme

        $.ajax({
            url: URL,
            method: 'POST',
            data: {
                ime: ime,
                prezime: prezime,
                mejl: mejl,
                file: file
            },
            success: function(data) {
                $('#rezultat').text('Uspeh! ' + ime + ' ' + prezime + ' ' + mejl);
            },
            error: function(error) {
                $('#rezultat').text('Greska prilikom obrade podataka.');
            }
        });
    });
});
