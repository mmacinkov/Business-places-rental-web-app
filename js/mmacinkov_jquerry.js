/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    var regexProvjera = new RegExp(/^[a-zA-Z0-9]+[.]?[a-zA-Z0-9]+@[a-zA-Z0-9]+[.]{1}[a-zA-Z]{2,}$/);
    $("#email").blur(function () {
        var uneseniEmail = $("#email").val();
        if (uneseniEmail.length <= 10)
        {
            $("#email").attr('class', 'greška');
            return false;
        } else if (uneseniEmail.length >= 30)
        {
            $("#email").attr('class', 'greška');
            return false;
        } else if (regexProvjera.test(uneseniEmail))
        {
            $("#email").attr('class', 'dobro');
        } else {
            $("#email").attr('class', 'greška');
            return false;
        }
    });



    var regexLozinka = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/);
    $("#lozinka1").blur(function () {
        var unesenaLozinka = $("#lozinka1").val();
        if (unesenaLozinka.length < 8)
        {
            $("#lozinka1").attr('class', 'greška');
            return false;
        } else if (regexLozinka.test(unesenaLozinka))
        {
            $("#lozinka1").attr('class', 'dobro');

        } else
        {
            $("#lozinka1").attr('class', 'greška');
            return false;
        }
    });
    $("#korime").blur(function () {
        var korime = $("#korime").val();
        jQuery.ajax({
            url: "provjeraKorime.php",
            data: {korime: korime},
            method: "POST",
            success: function (data) {
                if (data === "Korisničko ime nedostupno.") {
                    alert("GREŠKA! Korisničko ime je zauzeto! Birajte drugo.");
                    //$("#korime").attr('class', 'greška');
                } else if (data === "Korisničko ime dostupno.") {
                    alert("Korisničko ime je dostupno.");
                    //$("#korime").attr('class', 'dobro');
                }
            }
        });
    });

    var regexIme = /^[A-Z][A-Za-z]+$/;
    $("#ime").blur(function () {
        if (regexIme.test(document.getElementById("ime").value)) {
            $("#ime").attr('class', 'dobro');
        } else {
            $("#ime").attr('class', 'greška');
        }
    });

    var regexPrezime = /^[A-Z][A-Za-z]+$/;
    $("#prez").blur(function () {
        if (regexPrezime.test(document.getElementById("prez").value)) {
            $("#prez").attr('class', 'dobro');
        } else {
            $("#prez").attr('class', 'greška');
        }
    });



});

function brojiKlikove(id) {
        var id_oglasa = id;
        $.ajax({
            type: 'GET',
            url: 'brojacKlikova.php',
            data: {id: id_oglasa},
            success: function (kraj) {
                console.log(kraj);
            }
        });
    }
