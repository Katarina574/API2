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
                $('#rezultat').text(JSON.stringify(data.message, null, 2));
                // $('#rezultat').data.message;
            },
            error: function (error) {
                $('#rezultat').text('Greska prilikom obrade podataka.');
            }
        });
    });
});

let currentPage = 1; //pocetna stranica (menja se na klik - povecava ili smanjuje)
const usersPerPage = 5; //br entrija na strani
function fetchAndDisplayData() {
    const tableBody = document.querySelector('#korisnici-table tbody'); //hvata table sa id-jem definisanim u html

    const startIndex = (currentPage - 1) * usersPerPage; //u prvom 0
    const endIndex = startIndex + usersPerPage; // u prvom 5 (slice ne uzima zadnji)

    fetch('http://localhost:8086/api')
        .then(response => response.json())
        .then(data => {
            const usersToDisplay = data.slice(startIndex, endIndex);
            tableBody.innerHTML = '';

            usersToDisplay.forEach(korisnik => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${korisnik.id}</td>
                    <td>${korisnik.ime}</td>
                    <td>${korisnik.prezime}</td>
                    <td>${korisnik.mejl}</td>
                    <td>${korisnik.temperatura}</td>
                    <td>
                        <button class="delete-button btn btn-danger" data-id="${korisnik.id}">Obrisi</button>
                    </td>
                    <td>
                        <button class="download-button btn btn-success" data-id="${korisnik.id}">Preuzmi fajl</button>
                    </td>
                `;
                tableBody.appendChild(row);
                //event listener za dugme obrisi za svakog korisnika
                const deleteButton = row.querySelector('.delete-button');
                const downloadButton = row.querySelector('.download-button');

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
                downloadButton.addEventListener('click', function () {
                    const userId = korisnik.id;
                    downloadFile(userId);
                });
            });

            showPaginationControls(data.length);
        })
        .catch(error => {
            console.error('Greska prilikom preuzimanja podataka sa API-ja: ' + error);
        });
}


function downloadFile(userId) {
    const downloadUrl = `http://localhost:8086/api/download/${userId}`;
    const downloadLink = document.createElement('a');
    downloadLink.href = downloadUrl;
    downloadLink.click();
}


//prikazuje sve na ucitavanje stranice
document.addEventListener('DOMContentLoaded', fetchAndDisplayData);


function showPaginationControls(totalUsers) {
    const paginationDiv = document.querySelector('#pagination');
    const totalPages = Math.ceil(totalUsers / usersPerPage);

    const paginationHTML = `
        <div>Trenutna stranica: ${currentPage}</div>
        <button id="prevPage">Prethodna</button>
        <button id="nextPage">Sledeca</button>
    `;

    paginationDiv.innerHTML = paginationHTML;

    const prevPageButton = document.querySelector('#prevPage');

    // var prevPageButton = $('#prevPage');
    const nextPageButton = document.querySelector('#nextPage');

    prevPageButton.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            fetchAndDisplayData();
        }
    });

    nextPageButton.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            fetchAndDisplayData();
        }
    });
}


$(document).ready(function() {
    $('#search').on('input', function() {  //event listener za unos u polje pretrage
        var searchTerm = $(this).val(); //uzmi unesenu vrednost
        if (searchTerm === '') {
            $('#autosuggestResults').html(''); //ako je vrednost prazna - nista nije uneto ili je sve obrisano, da se ociste rezultati prethodno dobijeni u suggestions
        } else {
            fetchAutosuggestResults(searchTerm); //pozovi .js f-ju
        }
    });
});

function fetchAutosuggestResults(searchTerm) {
    $.ajax({
        url: 'http://localhost:8086/api/autosuggest',
        method: 'GET',
        data: { search: searchTerm },
        success: function(data) {
            displayAutosuggestResults(data);
        },
        error: function() {
            console.error('Error occurred during autosuggest.');
        }
    });
}

function displayAutosuggestResults(results) {
    var autosuggestResultsDiv = $('#autosuggestResults');
    autosuggestResultsDiv.html(''); //prazni div - npr svaki put kad dodamo novo slovo u search on registruje input (za koji proveri da li postoji ili ne preko php koda i rezultat prosledi u results) i pozove get i vrati success u funkciji iznad gde pozove ovu funkciju
    //ako ima rezultata on ih prikaze, za svako novo slovo on ponavlja sve, zato sam ovim gore praznimo div jer svaki put prodje ispocetka i ispisuje ili rezultat iz if ili rezultat iz else (odn. da nema rez.)
    if (results.length > 0) {
        results.forEach(function(result) {
            var resultString = JSON.stringify(result, null, 2); //da mi prikaze prave vrednosti a ne samo object
            autosuggestResultsDiv.append('<p>' + resultString + '</p>');
        });
    } else {
        autosuggestResultsDiv.append('<p>No autosuggest results.</p>');
    }
}
