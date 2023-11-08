// alert("Test da vidimo da li radi.");

$(document).ready(function () {
    $('#loadData').click(function () {
        var URL = 'http://localhost:8086/api';
        $.ajax({
            url: URL,
            method: 'GET',
            success: function (data) {
                $('#rezultat').text(JSON.stringify(data, null, 2));
            },
            error: function (error) {
                $('#rezultat').text('Greska prilikom obrade podataka.');
            }
        });
    });
});


$(function () {
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
            success: function (data) {
                $('#rezultat').text('Uspeh!');
            },
            error: function (error) {
                $('#rezultat').text('Greska prilikom obrade podataka.');
            }
        });
    });
});


//preuzimanje podataka sa API-ja i generisanje tabele
function fetchAndDisplayData() {
    const tableBody = document.querySelector('#korisnici-table tbody');

    fetch('http://localhost:8086/api')
        .then(response => response.json())
        .then(data => {
            data.forEach(korisnik => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${korisnik.id}</td>
                    <td>${korisnik.ime}</td>
                    <td>${korisnik.prezime}</td>
                    <td>${korisnik.mejl}</td>
                    <td>${korisnik.temperatura}</td>
                    <td>
                        <button class="delete-button" data-id="${korisnik.id}">Obrisi</button>
                    </td>
                `;
                tableBody.appendChild(row);
                //event listener za dugme obrisi za svakog korisnika
                const deleteButton = row.querySelector('.delete-button');
                deleteButton.addEventListener('click', function () {
                    const userId = korisnik.id;
                    fetch('http://localhost:8086/api/delete/' + userId, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({user_id: userId}),
                    })
                        .then(response => response.json())
                        .then(data => {
                            row.remove();
                        })
                        .catch(error => {
                            console.error('Greska prilikom brisanja korisnika: ' + error);
                        });
                });
            });
        })
        .catch(error => {
            console.error('Greska prilikom preuzimanja podataka sa API-ja: ' + error);
        });
}
//prikazuje na ucitavanje stranice
document.addEventListener('DOMContentLoaded', fetchAndDisplayData);



