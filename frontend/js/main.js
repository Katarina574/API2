// alert("Test da vidimo da li radi.");

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


$(function() {
    $('button[type=submit]').click(function (event) {
        event.preventDefault();

        var form = this.form;
        var data = new FormData(form);

        $.ajax({
            type: 'POST',
            url: 'http://localhost:8086/upload',
            data: data,
            processData: false,
            contentType: false,
                        success: function(data) {
                $('#rezultat').text('Uspeh!');
            },
            error: function(error) {
                $('#rezultat').text('Greska prilikom obrade podataka.');
            }
        });
    });
});


