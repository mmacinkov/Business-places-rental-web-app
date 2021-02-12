/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
window.onload = KreirajDogađaje();
function KreirajDogađaje() {
    var form = document.getElementById("registracija");
    form.addEventListener("submit",
            function (popuni) {
                for (var i = 0; i < form.elements.length; i++) {
                    if (form[i].value === "") {
                        alert('GREŠKA! Ispunite sva polja!');
                        return popuni.preventDefault();
                    }
                    if (/[!]/.test(form[i].value)) {
                        alert('GREŠKA! Pokušavate unijeti nedozvoljeni znak "!"!');
                        return popuni.preventDefault();
                    }
                    if (/[#]/.test(form[i].value)) {
                        alert('GREŠKA! Pokušavate unijeti nedozvoljeni znak "#"!');
                        return popuni.preventDefault();
                    }
                    if (/[?]/.test(form[i].value)) {
                        alert('GREŠKA! Pokušavate unijeti nedozvoljeni znak "?"!');
                        return popuni.preventDefault();
                    }
                    if (/[']/.test(form[i].value)) {
                        alert('GREŠKA! Pokušavate unijeti nedozvoljeni znak "\'"!');
                        return popuni.preventDefault();
                    }
                }
            }, false);

    var korime = document.getElementById("korime");
    korime.addEventListener("change",
            function (event) {
                korime = document.getElementById("korime").value.toString();
                var velikoSlovo = korime[0].toUpperCase();
                if (korime[0] !== velikoSlovo || korime.length < 5) {
                    document.getElementById("korime").style.backgroundColor = "Red";
                    return event.preventDefault();
                } else if (korime[0] === velikoSlovo || korime.length > 5) {
                    document.getElementById("korime").style.backgroundColor = "Green";
                    return event.preventDefault();
                }
            }, false);

    var lozinka1 = document.getElementById('lozinka1');
    var lozinka2 = document.getElementById('lozinka2');
    lozinka2.addEventListener("change",
            function (provjeri) {
                if (lozinka1.value === lozinka2.value) {
                    document.getElementById("lozinka2").style.backgroundColor = "Green";
                    return provjeri.preventDefault();
                } else {
                    document.getElementById("lozinka2").style.backgroundColor = "Red";
                    return provjeri.preventDefault();
                }
            });

    var datum = document.getElementById("datum");
    var najmanjiDatum = "01/01/2000";
    datum.addEventListener("change",
            function (event4) {
                datum = document.getElementById("datum").value;
                if (Date.parse(datum) > Date.parse(najmanjiDatum)) {
                    document.getElementById("datum").style.backgroundColor = "Red";
                    return event4.preventDefault();
                } else {
                    document.getElementById("datum").style.backgroundColor = "Green";
                    return event4.preventDefault();
                }
            }, false);

}
