<!DOCTYPE html>
<?php
require 'baza.class.php';
echo 'ADMIN';
?>
<html lang="hr">
    <head>
        <title>POSLOVNI PROSTOR</title>
        <meta charset="utf-8">
        <link href="css/mmacinkov2.css" rel="stylesheet" type="text/css" >
        <link href="css/mmacinkov_prilagodbe.css" rel="stylesheet" type="text/css" >
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css"> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> 
        <script src="js/mmacinkov_jquerry.js" type="text/javascript"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>

        <meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="naslov" content="Početna stranica">
        <meta name="datum promjene" content="30.05.2018.">
        <meta name="autor" content="Marin Mačinković">
    </head>
    <body>
        <header>
            <div id="google_translate_element"><script type="text/javascript">
                function googleTranslateElementInit() {
                    new google.translate.TranslateElement({pageLanguage: 'hr'}, 'google_translate_element');
                }
                </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            </div>
            <h1>POSLOVNI PROSTOR
            </h1>                     


        </header>
        <section>
            <nav>
                <ul>
                    <li><a href="pocetna.php" >Početna</a></li>
                    <li><a href="registracija.php" >Registracija</a></li>
                    <li><a href="prijava.php">Prijava</a></li>
                    <li><a href="o_autoru.html">O autoru</a></li>
                    <li><a href="dokumentacija.html">Dokumentacija</a></li>
                    <li><a href="odjava.php">Odjava</a></li>
                </ul>
            </nav>
        </section> 
        <div style="height: 25px">
            </div>
        <section>
            <?php
            session_start();
            $korisnikIme = "";
             if (isset($_SESSION['korisnik'])) {
                    $korisnikIme = $_SESSION['korisnik'];
                }
            $spojiBazu = new Baza();
            $spojiBazu->spojiDB();
            $upit200 = $spojiBazu->selectDB("SELECT FK_uloga FROM KORISNIK WHERE korisnicko_ime ='$korisnikIme'");
            $dohvatUloge = $upit200->fetch_array();
            if ($dohvatUloge[0] == 4) {
            $postranici = 10;
            if (isset($_GET["page"])) {
                $stranica = intval($_GET["page"]);
            } else {
                $stranica = 1;
            }
            $izracun = $postranici * $stranica;
            $start = $izracun - $postranici;
            $upit22 = $spojiBazu->selectDB("SELECT * FROM DNEVNIK_RADA LIMIT $start, $postranici");

            echo '<form action = administrator.php method="post">';
            if (isset($_POST['search2'])) {
                $query = $_POST['query2'];
                $upit22 = $spojiBazu->selectDB("SELECT korisnik, naziv, vrijeme, opis_radnje FROM DNEVNIK_RADA WHERE korisnik LIKE '%$query%' OR naziv LIKE '%$query%' OR opis_radnje LIKE '%$query%' OR vrijeme LIKE '%$query%'");
            }

            echo '<input class="pretrazivanje" type="text" name="query2">';
            echo '<input type="submit" name="search2" value="Pretraži">';
            echo '</form>';

            if ($upit22->num_rows > 0) {
                echo "<table><caption>DNEVNIK RADA</caption><tr><th>Korisnik</th><th>Naziv</th><th>Vrijeme</th><th>Opis</th></tr>";
                while ($row = $upit22->fetch_assoc()) {
                    echo "<tr><td>" . $row["korisnik"] . "</td><td>" . $row["naziv"] . "</td><td>" . $row["vrijeme"] . "</td><td>" . $row["opis_radnje"] . "</td></tr>";
                }
                echo "</table>";

                if (isset($stranica)) {
                    $upit19 = $spojiBazu->selectDB("SELECT COUNT(*) AS Ukupno FROM DNEVNIK_RADA");
                    if ($upit19->num_rows > 0) {
                        $rs = $upit19->fetch_assoc();
                        $ukupno = $rs["Ukupno"];
                        $ukupnoStranica = ceil($ukupno / $postranici);
                        if ($stranica <= 1) {
                            echo "<span id='poveznica' style='font-weight: bold;'>Prethodna </span>";
                        } else {
                            $j = $stranica - 1;
                            echo "<span><a id='poveznica2' href='administrator.php?page=$j'>< Prethodna </a></span>";
                        }
                        for ($i = 1; $i <= $ukupnoStranica; $i++) {
                            if ($i <> $stranica) {
                                echo "<span><a id='poveznica2' href='administrator.php?page=$i'>$i</a></span>";
                            } else {
                                echo "<span id='poveznica' style='font-weight: bold;'>$i</span>";
                            }
                        }
                        if ($stranica == $ukupnoStranica) {
                            echo "<span id='poveznica' style='font-weight: bold;'> Sljedeća </span>";
                        } else {
                            $j = $stranica + 1;
                            echo "<span><a id='poveznica2' href='administrator.php?page=$j'>Sljedeća ></a></span>";
                        }
                    }
                }
            } else {
                echo "0 results";
            }
            echo '<br><br><br>';
            $upit31 = $spojiBazu->selectDB("SELECT ID_korisnika, korisnicko_ime, ime, prezime, blokiran FROM KORISNIK WHERE blokiran='1'");
            if ($upit31->num_rows > 0) {
                echo "<table><caption>BLOKIRANI KORISNICI</caption><tr><th>Korisničko ime</th><th>Ime</th><th>Prezime</th><th>Status</th></tr>";
                while ($row = $upit31->fetch_assoc()) {
                    if ($row["blokiran"] == 1) {
                        $row["blokiran"] = 'Blokiran';
                    }
                    echo "<tr><td>" . $row["korisnicko_ime"] . "</td><td>" . $row["ime"] . "</td><td>" . $row["prezime"] . "</td><td>" . $row["blokiran"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }

            echo '<br><br>';
            echo '<form class="zaForme" action="administrator.php" method="post">';
            echo '<h3>OTKLJUČAVANJE KORISNIČKOG RAČUNA</h3>';
            echo 'Odaberite korisnika kojeg želite odblokirat: ';
            $upit32 = $spojiBazu->selectDB("SELECT ID_korisnika, korisnicko_ime, ime, prezime, blokiran FROM KORISNIK WHERE blokiran='1'");
            if ($upit32->num_rows > 0) {
                echo "<select name = 'naziv123'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit32->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_korisnika'] . '">' . $row['korisnicko_ime'] . "&nbsp;Ime: " . $row['ime'] . "&nbsp;Prezime: " . $row['prezime'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo '<input type="submit" name="odblokiraj" value="Odblokiraj">';
            echo '</form>';
            if (isset($_POST['odblokiraj'])) {
                $ID_korisnika = $_POST['naziv123'];
                $upit33 = $spojiBazu->updateDB("UPDATE KORISNIK SET blokiran='0' WHERE ID_korisnika='$ID_korisnika'");
                    $upit34 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Odblokiranje korisnika', now(), 'Korisnik je odblokiran')");
                }
            

            echo '<br><br>';
            $upit41 = $spojiBazu->selectDB("SELECT ID_korisnika, korisnicko_ime, ime, prezime, blokiran FROM KORISNIK WHERE blokiran='0'");
            if ($upit41->num_rows > 0) {
                echo "<table><caption>OTKLJUČANI KORISNICI</caption><tr><th>Korisničko ime</th><th>Ime</th><th>Prezime</th><th>Status</th></tr>";
                while ($row = $upit41->fetch_assoc()) {
                    if ($row["blokiran"] == 0) {
                        $row["blokiran"] = 'Otključan';
                    }
                    echo "<tr><td>" . $row["korisnicko_ime"] . "</td><td>" . $row["ime"] . "</td><td>" . $row["prezime"] . "</td><td>" . $row["blokiran"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }

            echo '<br><br>';            
            echo '<form class="zaForme" action="administrator.php" method="post">';
            echo '<h3>BLOKIRANJE KORISNIČKOG RAČUNA</h3>';
            echo 'Odaberite korisnika kojeg želite blokirat: ';
            $upit37 = $spojiBazu->selectDB("SELECT ID_korisnika, korisnicko_ime, ime, prezime, blokiran FROM KORISNIK WHERE blokiran='0'");
            if ($upit37->num_rows > 0) {
                echo "<select name = 'naziv1234'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit37->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_korisnika'] . '">' . $row['korisnicko_ime'] . "&nbsp;Ime: " . $row['ime'] . "&nbsp;Prezime: " . $row['prezime'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo '<input type="submit" name="blokiraj" value="Blokiraj">';
            echo '</form>';
            if (isset($_POST['blokiraj'])) {
                $ID_korisnika1 = $_POST['naziv1234'];
                $upit38 = $spojiBazu->updateDB("UPDATE KORISNIK SET blokiran='1' WHERE ID_korisnika='$ID_korisnika1'");
                $upit40 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Blokiranje korisnika', now(), 'Korisnik je blokiran')");
            }

            echo '<br><br>';
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>KREIRANJE NOVE LOKACIJE</h3>';
            echo 'Naziv: ';
            echo '<input type="text" name="nazivLokacije" required="required"><br>';
            echo 'Kontakt: ';
            echo '<input type="text" name="kontaktLokacije" required="required"><br>';
            echo '<input type="submit" name="dodajLokaciju" value="Dodaj lokaciju"><br>';
            echo '</form>';
            if (isset($_POST['dodajLokaciju'])) {
                $naziv = $_POST['nazivLokacije'];
                $kontakt = $_POST['kontaktLokacije'];
                $upit49 = $spojiBazu->selectDB("INSERT INTO LOKACIJA VALUES (default, '$naziv', '$kontakt')");
                $upit45 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Nova lokacija', now(), 'Admin je kreirao novu lokaciju')");
            }

            
            echo '<br><br>';
            echo '<form class="zaForme" novalidate id="registracija" action="administrator.php" method="post">';
            echo '<h3>KREIRANJE NOVOG MODERATORA</h3>';
            echo '<br><br>';
            echo '<label for="korime">Korisničko ime: </label>
                        <input type="text" id="korime" name="korime" maxlength="20" placeholder="korisničko ime"  required="required">
                        <br>
                        <label for="lozinka1">Lozinka: </label>
                        <input type="password" id="lozinka1" name="lozinka1" placeholder="lozinka" required="required">
                        <br>
                        <label for="lozinka2">Potvrdi lozinku: </label>
                        <input type="password" id="lozinka2" name="lozinka2" placeholder="lozinka" required="required">
                        <br>
                        <label for="email">Email adresa: </label>
                        <input type="email" id="email" name="email" placeholder="ime.prezime@posluzitelj.xxx" required="required">
                        <br>
                        <label for="ime">Ime: </label>
                        <input type="text" id="ime" name="ime" size="20" maxlength="30" placeholder="Ime">                        
                        <br>
                        <label for="prez">Prezime: </label>
                        <input type="text" id="prez" name="prez" size="20" maxlength="50" placeholder="Prezime">
                        <br>
                        <label for="datum">Datum rođenja: </label>
                        <input type="date" id="datum" name="datum"><br>
                        <label for="spol">Spol: </label>
                        <select id="spol" name="spol">
                            <option value="" selected="selected"></option>
                            <option value="muško">muško</option>
                            <option value="žensko">žensko</option> 
                        </select>    
                        <br> 
                    <div class="g-recaptcha" data-sitekey="6Le2SlwUAAAAAJWIIfN9rr9sw9QiCv9q8xY83D1Y"></div><br>
                    <input name="submit5" id="submit5" type="submit" value=" Dodaj moderatora ">';
            echo '</form>';

            if (isset($_POST['submit5'])) {
                $email = $_POST['email'];
                $datum = $_POST['datum'];
                $lozinka1 = $_POST['lozinka1'];
                $korime = $_POST['korime'];
                $prezime = $_POST['prez'];
                $ime = $_POST['ime'];
                $lozinka = $_POST['lozinka1'];
                $sol = sha1(time());
                $kriptiranaL = sha1($sol . "-" . $lozinka1);
                $spol = $_POST['spol'];

                $upit42 = $spojiBazu->selectDB("INSERT INTO KORISNIK VALUES(default, '$korime', '$lozinka1', '$kriptiranaL', '$ime', '$prezime', '$datum', '$email', '$spol', '3', '1', now(), '0', '0')");
                $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Dodavanje moderatora', now(), 'Dodavanje novog moderatora u bazu od strane admina')");
            }

            echo '<br><br>';
            echo '<form class="zaForme" action="administrator.php" method="post">';
            echo '<h3>PROMJENA ULOGE U MODERATORA</h3>';
            echo 'Odaberite registriranog korisnika kojeg želite unaprijediti u moderatora: ';
            $upit43 = $spojiBazu->selectDB("SELECT ID_korisnika, korisnicko_ime, ime, prezime, FK_uloga FROM KORISNIK WHERE FK_uloga='2'");
            if ($upit43->num_rows > 0) {
                echo "<select name = 'naziv12345'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit43->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_korisnika'] . '">' . $row['korisnicko_ime'] . "&nbsp;Ime: " . $row['ime'] . "&nbsp;Prezime: " . $row['prezime'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo '<input type="submit" name="promijeni" value="Promoviraj u moderatora">';
            echo '</form>';
            if (isset($_POST['promijeni'])) {
                $ID_korisnika = $_POST['naziv12345'];
                $upit44 = $spojiBazu->updateDB("UPDATE KORISNIK SET FK_uloga='3' WHERE ID_korisnika='$ID_korisnika'");
                
                $upit46 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Promjena uloge', now(), 'Korisnik je promoviran u moderatora')");
            }

            echo '<br><br>';

            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>DODJELJIVANJE MODERATORA LOKACIJI</h3>';
            echo 'Odaberite lokaciju: ';
            $upit48 = $spojiBazu->selectDB("SELECT * FROM LOKACIJA");
            if ($upit48->num_rows > 0) {
                echo "<select name = 'lokacijaOdabir'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit48->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_lokacije'] . '">' . $row['naziv'] . "&nbsp;Kontakt: " . $row['kontakt'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo 'Dodajte moderatora/e lokaciji<br>';
            $upit47 = $spojiBazu->selectDB("SELECT ID_korisnika, korisnicko_ime, ime, prezime, FK_uloga FROM KORISNIK WHERE FK_uloga='3'");
            if ($upit47->num_rows > 0) {
                echo "<select name = 'moderatorOdabir'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit47->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_korisnika'] . '">' . $row['korisnicko_ime'] . "&nbsp;Ime: " . $row['ime'] . "&nbsp;Prezime: " . $row['prezime'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo '<input type="submit" name="dodajModILok" value="Dodaj"><br>';
            echo '</form>';
            if (isset($_POST['dodajModILok'])) {
                $ID_lokacije = $_POST['lokacijaOdabir'];
                $ID_moderatora = $_POST['moderatorOdabir'];
                $upit50 = $spojiBazu->selectDB("INSERT INTO MODERATOR_LOKACIJE VALUES ('$ID_lokacije', '$ID_moderatora')");
                $upit51 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Dodavanje moderatora lokaciji', now(), 'Moderator je dodan odredenoj lokaciji')");
            }
            
            echo '<br><br>';
            
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>KREIRANJE POZICIJE</h3>';
            echo 'Odaberite stranicu: <br>';
            $upit74 = $spojiBazu->selectDB("SELECT ID_stranica, naziv FROM STRANICA");
            if ($upit74->num_rows > 0) { 
                echo "<select name = 'odabirStranice'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit74->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_stranica'] . '">' . $row['naziv'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo "0 results";
            }
            echo 'Unesite gdje želite poziconirati Vaš oglas: <br>';
            echo '<input type="text" name="pozicioniranje"><br>';
            echo 'Unesite širinu oglasa: <br>';
            echo '<input type="text" name="sirina"><br>';
            echo 'Unesite visinu oglasa: <br>';
            echo '<input type="text" name="visina"><br>';
            echo 'Dodjelite moderatora: <br>';
            $upit75 = $spojiBazu->selectDB("SELECT ID_korisnika, ime, prezime FROM KORISNIK WHERE FK_uloga = '3'");
            if ($upit75->num_rows > 0) { 
                echo "<select name = 'odabirModeratora'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit75->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_korisnika'] . '">' . $row['ime'] . " " . $row['prezime'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo "0 results";
            }
            echo '<input type="submit" name="dodajPoziciju" value="Dodaj">';
            echo '</form>';
            if(isset($_POST['dodajPoziciju'])){
                $IDStranice = $_POST['odabirStranice'];
                $pozicioniranje = $_POST['pozicioniranje']; 
                $sirina = $_POST['sirina']; 
                $visina = $_POST['visina']; 
                $moderator = $_POST['odabirModeratora'];
                $upit76 = $spojiBazu->selectDB("INSERT INTO POZICIJA VALUES (default, '$pozicioniranje', '$visina', '$sirina', '$moderator')"); 
                $upit77 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES(default, '$korisnikIme', 'Kreiranje nove pozicije', now(), 'Admin je kreirao novu poziciju')");
                $upit78 = $spojiBazu->selectDB("SELECT ID_pozicija FROM POZICIJA WHERE pozicioniranje='$pozicioniranje' AND visina='$visina' AND sirina='$sirina' AND FK_korisnik='$moderator'");
                $IDPozicija = $upit78->fetch_array(); 
                $upit79 = $spojiBazu->selectDB("INSERT INTO STRANICA_POZICIJA VALUES (default, '$IDPozicija[0]', '$IDStranice')"); 
                $upit80 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES(default, '$korisnikIme', 'Dodavanje pozicije stranice', now(), 'Admin je dodao novu poziciju odabranoj stranici')");
            }
            
            echo '<br><br>';
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>AŽURIRAJ VISINU I ŠIRINU PRIKAZIVANJA</h3>';
            echo 'Odaberite poziciju: <br>';
            $upit81 = $spojiBazu->selectDB("SELECT ID_pozicija, pozicioniranje FROM POZICIJA");
            if ($upit81->num_rows > 0) { 
                echo "<select name = 'odabirPoz'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit81->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_pozicija'] . '">' . $row['ID_pozicija'] . " " . $row['pozicioniranje'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo "0 results";
            }
            echo 'Unesi novu širinu: <br>';
            echo '<input type="text" name="sirina2"><br>';
            echo 'Unesite novu visinu: <br>';
            echo '<input type="text" name="visina2"><br>';
            echo '<input type="submit" name="azurirajPoz" value="Ažuriraj">';
            echo '</form>';
            if(isset($_POST['azurirajPoz'])){
                $IDPoze = $_POST['odabirPoz']; 
                $sirina2 = $_POST['sirina2']; 
                $visina2 = $_POST['visina2']; 
                $upit82 = $spojiBazu->selectDB("UPDATE POZICIJA SET sirina='$sirina2', visina='$visina2' WHERE ID_pozicija='$IDPoze'");
            }
            echo '<br><br>';
            $upit111 = $spojiBazu->selectDB("SELECT k.korisnicko_ime, o.naziv, o.broj_klikova FROM OGLAS o JOIN KORISNIK k ON k.ID_korisnika=o.FK_korisnik");
                if (isset($_POST['ASC12'])) {
                    $upit111 = $spojiBazu->selectDB("SELECT k.korisnicko_ime, o.naziv, o.broj_klikova FROM OGLAS o JOIN KORISNIK k ON k.ID_korisnika=o.FK_korisnik ORDER BY broj_klikova ASC");
                } elseif (isset($_POST['DESC12'])) {
                    $upit111 = $spojiBazu->selectDB("SELECT k.korisnicko_ime, o.naziv, o.broj_klikova FROM OGLAS o JOIN KORISNIK k ON k.ID_korisnika=o.FK_korisnik ORDER BY broj_klikova DESC");
                }
                 if (isset($_POST['search12'])) {
                $query = $_POST['query12'];
                $upit111 = $spojiBazu->selectDB("SELECT k.korisnicko_ime, o.naziv, o.broj_klikova FROM OGLAS o JOIN KORISNIK k ON k.ID_korisnika=o.FK_korisnik WHERE korisnicko_ime LIKE '%$query%'");
            }
            echo '<form action="administrator.php" method="post">';
            echo 'Sortiraj po broju klikova: &nbsp;&nbsp;&nbsp';
            echo '<input type="submit" name="ASC12" value="Uzlazno"> &nbsp;&nbsp';
            echo '<input type="submit" name="DESC12" value="Silazno"><br>';
            echo '<input class="pretrazivanje" type="text" name="query12">';
            echo '<input type="submit" name="search12" value="Pretraži">';
                
                if ($upit111->num_rows > 0) {
                    echo '<table><caption>STATISTIKA KLIKOVA SVIH KORISNIKA</caption><tr><th>Korisnik</th><th>Naziv</th><th>Broj klikova</th></tr>';
                    while ($row = $upit111->fetch_array()) {

                        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
                echo '</form>';
                
                echo '<br><br>';
                echo '<br><br>';
                echo '<h1>NEREGISTRIRANI</h1>';
                $postr = 10;
            if (isset($_GET["page"])) {
                $stranica = intval($_GET["page"]);
            } else {
                $stranica = 1;
            }
            $izracunaj = $postr * $stranica;
            $startaj = $izracunaj - $postr;
            $upit20 = $spojiBazu->selectDB("SELECT * FROM LOKACIJA LIMIT $startaj, $postr");

            if (isset($_POST['ASC'])) {
                $upit20 = $spojiBazu->selectDB("SELECT * FROM LOKACIJA ORDER BY naziv ASC LIMIT 10");
            } elseif (isset($_POST['DESC'])) {
                $upit20 = $spojiBazu->selectDB("SELECT * FROM LOKACIJA ORDER BY naziv DESC LIMIT 10");
            }
            if (isset($_POST['ASCID'])) {
                $upit20 = $spojiBazu->selectDB("SELECT * FROM LOKACIJA ORDER BY ID_lokacije ASC LIMIT 10");
            } elseif (isset($_POST['DESCID'])) {
                $upit20 = $spojiBazu->selectDB("SELECT * FROM LOKACIJA ORDER BY ID_lokacije DESC LIMIT 10");
            }

            if (isset($_POST['search'])) {
                $query = $_POST['query'];
                $upit20 = $spojiBazu->selectDB("SELECT * FROM LOKACIJA WHERE naziv LIKE '%$query%' OR ID_lokacije LIKE '%$query%' OR kontakt LIKE '%$query%' LIMIT 10");
            }

            echo '<br><br>';
            echo '<form action="administrator.php" method="post">';
            echo 'Sortiraj po ID-u: &nbsp;&nbsp;&nbsp';
            echo '<input type="submit" name="ASCID" value="Uzlazno"> &nbsp;&nbsp';
            echo '<input type="submit" name="DESCID" value="Silazno"><br>';
            echo 'Sortiraj po nazivu: ';
            echo '<input type="submit" name="ASC" value="Uzlazno"> &nbsp;&nbsp';
            echo '<input type="submit" name="DESC" value="Silazno"><br>';
            echo '<input class="pretrazivanje" type="text" name="query">';
            echo '<input type="submit" name="search" value="Pretraži">';

            if ($upit20->num_rows > 0) {
                echo '<table><caption>LOKACIJA</caption><tr><th>ID lokacije</th><th>Naziv lokacije</th><th>Kontakt</th></tr>';
                while ($row = $upit20->fetch_assoc()) {

                    echo "<tr><td>" . $row["ID_lokacije"] . "</td><td><a href='izlistajDvorane.php?id=" . $row['ID_lokacije'] . "'>" . $row["naziv"] . "</a></td><td>" . $row["kontakt"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }


            echo '</form>';

            if (isset($stranica)) {
                $upit20 = $spojiBazu->selectDB("SELECT COUNT(*) AS Ukupno FROM LOKACIJA");
                if ($upit20->num_rows > 0) {
                    $rs = $upit20->fetch_assoc();
                    $ukupno = $rs["Ukupno"];
                    $ukupnoStranica = ceil($ukupno / $postr);
                    if ($stranica <= 1) {
                        echo "<span id='poveznica' style='font-weight: bold;'>Prethodna </span>";
                    } else {
                        $j = $stranica - 1;
                        echo "<span><a id='poveznica2' href='administrator.php?page=$j'>< Prethodna </a></span>";
                    }
                    for ($i = 1; $i <= $ukupnoStranica; $i++) {
                        if ($i <> $stranica) {
                            echo "<span><a id='poveznica2' href='administrator.php?page=$i'>$i</a></span>";
                        } else {
                            echo "<span id='poveznica' style='font-weight: bold;'>$i</span>";
                        }
                    }
                    if ($stranica == $ukupnoStranica) {
                        echo "<span id='poveznica' style='font-weight: bold;'> Sljedeća </span>";
                    } else {
                        $j = $stranica + 1;
                        echo "<span><a id='poveznica2' href='administrator.php?page=$j'>Sljedeća ></a></span>";
                    }
                }
            }
                
                echo '<br><br>';
                echo '<h1>REGISTRIRANI</h1>';
                echo '<br><br>';
            echo '<form novalidate class="zaForme" action="administrator.php" method="post">';
            echo '<h3>ZAHTJEV ZA BLOKIRANJE OGLASA</h3>';
            echo 'Odaberite oglas: ';
            $upit25 = $spojiBazu->selectDB("SELECT ID_oglasa, naziv, web_adresa FROM OGLAS WHERE FK_status=2");
            if ($upit25->num_rows > 0) {
                echo "<select name = 'zahtjevBlok'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit25->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_oglasa'] . '">' . $row['naziv'] . "&nbsp;Web adresa: " . $row['web_adresa'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo 'Navedite razlog: ';
            echo '<input type="text" name="razlog" required="required"><br>';
            echo '<input type="submit" name="zahtjevZaBlok" value="Odaberi"> &nbsp';
            echo '</form>';
            
            
            if(isset($_POST['zahtjevZaBlok'])){
                $ID_oglasa = $_POST['zahtjevBlok']; 
                $razlog = $_POST['razlog'];
                $upit26 = $spojiBazu->selectDB("UPDATE OGLAS SET razlog='$razlog', zahtjev_za_blok='1', vrijeme_zahtjeva=now() WHERE ID_oglasa='$ID_oglasa'");
                $upit27 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Zahtjev za blokiranje oglasa', now(), 'Korisnik je poslao zahtjev za blokiranje odredenog oglasa')");
            }
            
            echo '<br><br>';
            $upit21 = $spojiBazu->selectDB("SELECT naziv, cijena, vrijeme_trajanja FROM VRSTA_OGLASA");
            if ($upit21->num_rows > 0) {
                echo '<table><caption>VRSTE OGLASA</caption><tr><th>Vrsta oglasa</th><th>Cijena</th><th>Vrijeme trajanja</th></tr>';
                while ($row = $upit21->fetch_assoc()) {

                    echo "<tr><td>" . $row["naziv"] . "</td><td>" . $row["cijena"] . "</td><td>" . $row["vrijeme_trajanja"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }

            echo '<br><br>';
            echo '<form enctype="multipart/form-data" class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>ZAHTJEV ZA KREIRANJE NOVOG OGLASA</h3>';
            echo 'Vrsta oglasa: <br>';
            $upit22 = $spojiBazu->selectDB("SELECT ID_vrsta, naziv FROM VRSTA_OGLASA");

            if ($upit22->num_rows > 0) {
                echo "<select name = 'odabirVrste'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit22->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_vrsta'] . '">' . $row["naziv"] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo 'Odaberite datum i vrijeme aktivacije oglasa: <br>';
            echo '<input type="datetime-local" name="vrijemeAktivacije"><br>';
            echo 'Naziv: <br>';
            echo '<input type="text" name="nazivOglasa"><br>';
            echo 'Web adresa: <br>';
            echo '<input type="text" name="webadresa"><br>';
            echo '<input type="hidden" name="MAX_FILE_SIZE" value="300000">
                Učitajte sliku za oglas: <input name="userfile" type="file"><br>';
            echo '<input type="submit" name="dodajZahtjev" value="Pošalji zahtjev"><br>';
            echo '</form>';
            if (isset($_POST['dodajZahtjev'])) {
                $ID_vrsta=$_POST['odabirVrste'];
                $vrijeme_aktivacije=$_POST['vrijemeAktivacije']; 
                $nazivOglasa = $_POST['nazivOglasa']; 
                $webAdresa=$_POST['webadresa'];
                $userfile = $_FILES['userfile']['tmp_name'];
                $userfile_name = $_FILES['userfile']['name'];
                $userfile_size = $_FILES['userfile']['size'];
                $userfile_error = $_FILES['userfile']['error'];
                if ($userfile_error > 0) {
                    echo 'Problem: ';
                    switch ($userfile_error) {
                        case 1: echo 'Veličina veća od ' .
                            ini_get('upload_max_filesize');
                            break;
                        case 2: echo 'Veličina veća od ' .
                            $_POST["MAX_FILE_SIZE"] . 'B';
                            break;
                        case 3: echo 'Datoteka djelomično prenesena';
                            break;
                        case 4: echo 'Datoteka nije prenesena';
                            break;
                    }
                }

                $upfile = 'multimedija/' . $userfile_name;
                if (is_uploaded_file($userfile)) {
                    if (!move_uploaded_file($userfile, $upfile)) {
                        echo 'Problem: nije moguće prenijeti datoteku na odredište';
                        
                    }else{
                        $upit23 = $spojiBazu->selectDB("SELECT ID_korisnika FROM KORISNIK WHERE korisnicko_ime='$korisnikIme'");
                $ID_kor = $upit23->fetch_array();
                $upit22 = $spojiBazu->selectDB("INSERT INTO OGLAS VALUES (default, '$nazivOglasa', '$webAdresa', '$userfile_name', '$vrijeme_aktivacije', '0', '0', '0', 'NULL', '0000-00-00 00:00:00', '1', '$ID_vrsta', '$ID_kor[0]', '1', '7')");
                $upit27 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Zahtjev za kreiranje novog oglasa', now(), 'Korisnik je poslao zahtjev za kreiranje novog oglasa')");
                
                
                    }
                } 
                
                
            }

            echo '<br><br>';
            echo '<h3>GALERIJA</h3>';
            $upit29 = $spojiBazu->selectDB("SELECT ID_korisnika FROM KORISNIK WHERE korisnicko_ime='$korisnikIme'"); 
            $IDKorisnika = $upit29->fetch_array();
            $upit28 = $spojiBazu->selectDB("SELECT slika_naziv FROM OGLAS WHERE FK_korisnik='$IDKorisnika[0]'");
            if ($upit28->num_rows > 0) {
                while($slikaNaziv = $upit28->fetch_array()){
                echo '<img src="multimedija/'.$slikaNaziv[0].'" alt='.$slikaNaziv[0].' height="300" width="400">&nbsp;&nbsp;';
                }
            }
            
            echo '<br><br>';
            $upit70 = $spojiBazu->selectDB("SELECT o.naziv, s.naziv FROM OGLAS o JOIN STATUS s ON s.ID_statusa=o.FK_status WHERE o.FK_korisnik='$IDKorisnika[0]'");
            if ($upit70->num_rows > 0) {
                echo '<table><caption>STATUS OGLASA</caption><tr><th>Oglasa</th><th>Status</th></tr>';
                while ($row = $upit70->fetch_array()) {

                    echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
            echo '<br><br>';
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>AŽURIRANJE OGLASA KOJI SU U PRIPREMI</h3>';
            echo 'Odaberite oglas: <br>';
            $upit71 = $spojiBazu->selectDB("SELECT o.ID_oglasa, o.naziv FROM OGLAS o JOIN STATUS s ON s.ID_statusa=o.FK_status WHERE o.FK_korisnik='$IDKorisnika[0]' AND FK_status='1'");
            if ($upit71->num_rows > 0) { 
                echo "<select name = 'azurirajOglas'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit71->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_oglasa'] . '">' . $row['naziv'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo "0 results";
            }
            echo 'Odaberite datum i vrijeme aktivacije oglasa: <br>';
            echo '<input type="datetime-local" name="vrijemeAktivacije2"><br>';
            echo 'Naziv: <br>';
            echo '<input type="text" name="nazivOglasa2"><br>';
            echo 'Web adresa: <br>';
            echo '<input type="text" name="webadresa2"><br>';
            echo '<input type="submit" name="azuriraj" value="Ažuriraj">';
            echo '</form>';
            if(isset($_POST['azuriraj'])){
                $IDOdabir = $_POST['azurirajOglas']; 
                $vrAkt = $_POST['vrijemeAktivacije2']; 
                $nazA = $_POST['nazivOglasa2']; 
                $web2 = $_POST['webadresa2']; 
                $upit72 = $spojiBazu->selectDB("UPDATE OGLAS SET naziv='$nazA', web_adresa='$web2', vrijeme_aktivacije='$vrAkt' WHERE ID_oglasa='$IDOdabir'");
                $upit73 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES(default, '$korisnikIme', 'Azuriranje oglasa', now(), 'Korisnik je azurirao jedan od oglasa u pripremi')");
            }
               
            $upit110 = $spojiBazu->selectDB("SELECT naziv, broj_klikova FROM OGLAS WHERE FK_korisnik='$IDKorisnika[0]'");
                if (isset($_POST['ASC12'])) {
                    $upit110 = $spojiBazu->selectDB("SELECT naziv, broj_klikova FROM OGLAS WHERE FK_korisnik='$IDKorisnika[0]' ORDER BY broj_klikova ASC");
                } elseif (isset($_POST['DESC12'])) {
                    $upit110 = $spojiBazu->selectDB("SELECT naziv, broj_klikova FROM OGLAS WHERE FK_korisnik='$IDKorisnika[0]' ORDER BY broj_klikova DESC");
                }
                 if (isset($_POST['search12'])) {
                $query = $_POST['query12'];
                $upit110 = $spojiBazu->selectDB("SELECT naziv, broj_klikova FROM OGLAS WHERE FK_korisnik='$IDKorisnika[0]' AND naziv LIKE '%$query%'");
            }
            echo '<form action="administrator.php" method="post">';
            echo 'Sortiraj po broju klikova: &nbsp;&nbsp;&nbsp';
            echo '<input type="submit" name="ASC12" value="Uzlazno"> &nbsp;&nbsp';
            echo '<input type="submit" name="DESC12" value="Silazno"><br>';
            echo '<input class="pretrazivanje" type="text" name="query12">';
            echo '<input type="submit" name="search12" value="Pretraži">';
                
                if ($upit110->num_rows > 0) {
                    echo '<table><caption>STATISTIKA KLIKOVA</caption><tr><th>Naziv</th><th>Broj klikova</th></tr>';
                    while ($row = $upit110->fetch_array()) {

                        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
                
                echo '</form>';
                
                
                echo '<br><br>';
                echo '<h1>MODERATOR</h1>';
                echo '<br><br>';
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>KREIRANJE NOVE DVORANE</h3>';
            echo 'Naziv dvorane: ';
            echo '<input type = "text" name="nazivDvorane"><br>';
            echo 'Namjena: ';
            echo '<input type = "text" name="namjenaDvorane"><br>';
            echo 'Broj mjesta: ';
            echo '<input type = "number" name="brojMjesta" min="5" max="1000"><br>';
            echo 'Odaberite lokaciju: ';
            $upit52 = $spojiBazu->selectDB("SELECT l.ID_lokacije, l.naziv FROM KORISNIK k JOIN MODERATOR_LOKACIJE ml ON k.ID_korisnika = ml.FK_korisnik JOIN LOKACIJA l ON l.ID_lokacije = ml.FK_lokacija WHERE k.FK_uloga = '3' AND k.korisnicko_ime = '$korisnikIme'");
            if ($upit52->num_rows > 0) {
                echo "<select name = 'lokacijaIzaberi'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit52->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_lokacije'] . '">' . $row['naziv'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo 'Odaberite termin: ';
            $upit53 = $spojiBazu->selectDB("SELECT * FROM TERMIN");
            if ($upit53->num_rows > 0) {
                echo "<select name = 'terminIzaberi'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit53->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_termina'] . '">' . $row['rok_prijave'] . "&nbsp;Vrijeme početka: " . $row['vrijeme_pocetka'] . "&nbsp;Vrijeme završetka: " . $row['vrijeme_zavrsetka'] .'</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo '<input type="submit" name="dodajNovuDvoranu" value="Dodaj dvoranu"><br>';
            echo '</form>';
            if (isset($_POST['dodajNovuDvoranu'])) {
                $ID_lokacije = $_POST['lokacijaIzaberi'];
                $nazivDvorane = $_POST['nazivDvorane'];
                $namjenaDvorane = $_POST['namjenaDvorane'];
                $brojMjesta = $_POST['brojMjesta'];
                $upit53 = $spojiBazu->selectDB("INSERT INTO DVORANA VALUES (default, '$nazivDvorane', '$namjenaDvorane', '$brojMjesta', '$ID_lokacije')");
                $upit54 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Kreiranje nove dvorane', now(), 'Kreirana je nova dvorana')");
                $upit55 = $spojiBazu->selectDB("SELECT ID_dvorane FROM DVORANA WHERE naziv='$nazivDvorane'");
                $ID_dvorane = $upit55->fetch_array();
                $ID_termina = $_POST['terminIzaberi'];
                $upit56 = $spojiBazu->selectDB("INSERT INTO ZAUZETOST_DVORANE VALUES ('$ID_termina', '$ID_dvorane[0]', '1')");
                $upit57 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Dodjeljivanje termina dvorani', now(), 'Termin je dodijeljen odredenoj dvorani')");
                
            }
            
            echo '<br><br>';
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>KREIRANJE NOVOG TERMINA</h3>';
            echo 'Rok prijave: ';
            echo '<input type="datetime-local" name="rokPrijave"><br>';
            echo 'Vrijeme početka: ';
            echo '<input type="datetime-local" name="vrijemePo"><br>';
            echo 'Vrijeme završetka: ';
            echo '<input type="datetime-local" name="vrijemeZa" min="5" max="1000"><br>';
            echo '<input type="submit" name="dodajTermin" value="Dodaj termin"><br>';
            echo '</form>';
            if(isset($_POST['dodajTermin'])){
                $rokPrijave = $_POST['rokPrijave']; 
                $vrijemePo = $_POST['vrijemePo']; 
                $vrijemeZa = $_POST['vrijemeZa'];
                $upit56 = $spojiBazu->selectDB("INSERT INTO TERMIN VALUES (default, '$rokPrijave', '$vrijemePo', '$vrijemeZa')");
                $upit57 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Kreiranje novog termina', now(), 'Kreiran je novi termin')");               
            }
            
            echo '<br><br>';
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>OTKAZIVANJE TERMINA</h3>';
            echo 'Odaberite termin koji želite otkazati: ';
            $upit58 = $spojiBazu->selectDB("SELECT zd.FK_termin, d.naziv, t.rok_prijave, t.vrijeme_pocetka, t.vrijeme_zavrsetka FROM TERMIN t JOIN ZAUZETOST_DVORANE zd ON t.ID_termina=zd.FK_termin JOIN DVORANA d ON d.ID_dvorane=zd.FK_dvorana WHERE zd.status='1'");
            if ($upit58->num_rows > 0) {
                echo "<select name = 'odabirTermina'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit58->fetch_array()) {             
                    echo '<option  value="' . $row['FK_termin'] . '">' . $row[1] . "&nbsp;Rok prijave: " . $row[2] . "&nbsp;Vrijeme početka: " . $row[3] . "&nbsp;Vrijeme završetka: " . $row[4] .'</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo '<input type="submit" name="otkazi" value="Otkaži"><br>';
            echo '</form>';
            if(isset($_POST['otkazi'])){
                $odabirTermina = $_POST['odabirTermina'];
                $upit63 = $spojiBazu->selectDB("SELECT FK_dvorana FROM ZAUZETOST_DVORANE WHERE FK_termin='$odabirTermina'");
                $odabirDvorane = $upit63->fetch_array();
                $upit59 = $spojiBazu->updateDB("UPDATE ZAUZETOST_DVORANE SET status='0' WHERE FK_termin='$odabirTermina' AND FK_dvorana='$odabirDvorane[0]'");
                $upit60 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Otkazivanje termina', now(), 'Moderator je otkazao termin')");
            }
            
            echo '<br><br>';
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>DODJELA NOVOG TERMINA</h3>';
            echo 'Odaberite zamjenski termin: ';
            $upit64 = $spojiBazu->selectDB("SELECT zd.FK_termin, d.naziv, t.rok_prijave, t.vrijeme_pocetka, t.vrijeme_zavrsetka FROM TERMIN t JOIN ZAUZETOST_DVORANE zd ON t.ID_termina=zd.FK_termin JOIN DVORANA d ON d.ID_dvorane=zd.FK_dvorana WHERE zd.status='0'");
            if ($upit64->num_rows > 0) {
                echo "<select name = 'odaberiTermin'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit64->fetch_array()) {
                    echo '<option  value="' . $row['FK_termin'] . '">' . $row[1] . "&nbsp;Rok prijave: " . $row[2] . "&nbsp;Vrijeme početka: " . $row[3] . "&nbsp;Vrijeme završetka: " . $row[4] .'</option>';
                } 
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo '<input type="submit" name="zamijeni" value="Odaberi"><br>';
            echo '</form>';
            if(isset($_POST['zamijeni'])){
                $odabirTermina = $_POST['odaberiTermin'];
                $upit63 = $spojiBazu->selectDB("SELECT FK_dvorana FROM ZAUZETOST_DVORANE WHERE FK_termin='$odabirTermina'");
                $odabirDvorane = $upit63->fetch_array();
                $upit59 = $spojiBazu->updateDB("UPDATE ZAUZETOST_DVORANE SET status='1' WHERE FK_termin='$odabirTermina' AND FK_dvorana='$odabirDvorane[0]'");
                $upit60 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Dodjela zamjenskog termina', now(), 'Moderator je odredio zamjenski termin')");
                $upit65 = $spojiBazu->selectDB("SELECT KORISNIK.email FROM KORISNIK, POVRATNA_INFORMACIJA, DVORANA, TERMIN, ZAUZETOST_DVORANE WHERE KORISNIK.id_korisnika = POVRATNA_INFORMACIJA.fk_korisnik AND POVRATNA_INFORMACIJA.fk_dvorana = DVORANA.id_dvorane AND ZAUZETOST_DVORANE.fk_dvorana = DVORANA.id_dvorane AND ZAUZETOST_DVORANE.fk_termin = TERMIN.id_termina AND POVRATNA_INFORMACIJA.FK_korisnik='54' GROUP BY KORISNIK.email");
                $email = $upit65->fetch_array(); 
                $naslov = "Dodjela novog termina";
                $upit66 = $spojiBazu->selectDB("SELECT t.rok_prijave, t.vrijeme_pocetka, t.vrijeme_zavrsetka FROM TERMIN t JOIN ZAUZETOST_DVORANE zd ON FK_termin='$odabirTermina'");
                $termin = $upit66->fetch_array();
                $poruka = "
Nazalost Vas termin je otkazan! 
Dodijeljen Vam je novi termin: 
------------------------
Rok prijave: $termin[0]
------------------------
";
                mail($email[0], $naslov, $poruka);
                
            }
            echo '<br><br>';
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>KREIRANJE NOVE VRSTE OGLASA</h3>';
            echo 'Naziv: <br>';
            echo '<input type="text" name="nazivVrste"><br>';
            echo 'Odaberite vrijeme trajanja: <br>';
            echo '<input type="time" name="vrijemeTra"><br>';
            echo 'Cijena(kn): <br>';
            echo '<input type="number" name="cijena"><br>';
            echo 'Odaberite brzinu izmjene(u s): <br>';
            echo '<input type="number" name="brzina"><br>';
            echo '<input type="submit" name="kreirajV" value="Kreiraj">';
            echo '</form>';
           
            if(isset($_POST['kreirajV'])){
                $nazivVrste = $_POST['nazivVrste']; 
                $vrijemeTrajanja = $_POST['vrijemeTra']; 
                $cijena = $_POST['cijena']; 
                $brzinaIzmjene = $_POST['brzina']; 
                $upit83 = $spojiBazu->selectDB("INSERT INTO VRSTA_OGLASA VALUES (default, '$nazivVrste', '$cijena', '$vrijemeTrajanja', '$brzinaIzmjene')");
                $upit84 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES(default, '$korisnikIme', 'Kreiranje nove vrste oglasa', now(), 'Moderator je kreirao novu vrstu oglasa')");         
            }
            
            
            echo '<br><br>';
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>POPIS ZAHTJEVA ZA OGLAŠAVANJE</h3>';
            echo 'Popis zahtjeva: <br>';
            $upit85 = $spojiBazu->selectDB("SELECT O.ID_oglasa, O.naziv, O.web_adresa FROM POZICIJA P JOIN STRANICA_POZICIJA SP ON P.ID_pozicija = SP.FK_pozicija JOIN OGLAS O ON SP.ID_strpoz=O.FK_stripoz WHERE P.FK_korisnik='$IDKorisnika[0]' AND O.FK_status='1' AND O.zahtjev_za_novi='1'");
            if ($upit85->num_rows > 0) { 
                echo "<select name = 'popisZah'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit85->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_oglasa'] . '">' . $row['naziv'] . " " . $row['web_adresa'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo "0 results";
            }
            echo '<input type="submit" name="prihvati" value="Prihvati">&nbsp;&nbsp;';
            echo '<input type="submit" name="odbij" value="Odbij">';
            echo '</form>';
            
            if(isset($_POST['prihvati'])){
                $IDOglas = $_POST['popisZah']; 
                $upit86 = $spojiBazu->selectDB("UPDATE OGLAS SET zahtjev_za_novi='0', FK_status='2' WHERE ID_oglasa='$IDOglas'");
                $upit87 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES(default, '$korisnikIme', 'Prihvaćanje zahtjeva za oglas', now(), 'Moderator je prihvatio zahtjev za oglasavanje')");                    
            }
            if(isset($_POST['odbij'])){
                $IDOglasa = $_POST['popisZah']; 
                $upit88 = $spojiBazu->selectDB("DELETE FROM OGLAS WHERE ID_oglasa='$IDOglasa'");
                $upit89 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES(default, '$korisnikIme', 'Odbijanje zahtjeva za oglas', now(), 'Moderator je odbio zahtjev za oglasavanje')");
            }
            
            echo '<br><br>';
            echo '<form class="zaForme" novalidate action="administrator.php" method="post">';
            echo '<h3>POPIS ZAHTJEVA ZA BLOKIRANJE OGLASA</h3>';
            echo 'Popis zahtjeva: <br>';
            $upit90 = $spojiBazu->selectDB("SELECT O.ID_oglasa, O.naziv, O.web_adresa FROM POZICIJA P JOIN STRANICA_POZICIJA SP ON P.ID_pozicija = SP.FK_pozicija JOIN OGLAS O ON SP.ID_strpoz=O.FK_stripoz WHERE P.FK_korisnik='$IDKorisnika[0]' AND O.FK_status='2' AND O.zahtjev_za_blok='1'");
            if ($upit90->num_rows > 0) { 
                echo "<select name = 'popisZaBlok'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit90->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_oglasa'] . '">' . $row['naziv'] . " " . $row['web_adresa'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo "0 results";
            }
            echo '<input type="submit" name="blokiraj" value="Blokiraj">';
            echo '</form>';
            
            if(isset($_POST['blokiraj'])){
                $IDBlok = $_POST['popisZaBlok']; 
                $upit91 = $spojiBazu->selectDB("UPDATE OGLAS SET zahtjev_za_blok='0', FK_status='3', blokiran='1', razlog='NULL', vrijeme_zahtjeva='0000-00-00 00:00:00' WHERE ID_oglasa='$IDBlok'");
                $upit92 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES(default, '$korisnikIme', 'Blokiranje oglasa', now(), 'Moderator je blokirao određeni oglas')");                    
            }
            
                
                echo '<br><br>';
            
            
            } else {
                header('Location: pocetna.php');
            }
            $spojiBazu->zatvoriDB();
            ?>

        </section>
        <div style="height: 35px">
            </div>
        <footer>
            <p> &copy; 24 M.Mačinković</p>
        </footer>


        <script src="js/mmacinkov.js" type="text/javascript"></script>
    </body>
</html>
