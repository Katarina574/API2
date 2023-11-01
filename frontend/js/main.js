alert("Test da vidimo da li radi.");

$(document).ready(function() {
    $('#loadData').click(function() {
        var URL = 'http://localhost:8080/api';

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