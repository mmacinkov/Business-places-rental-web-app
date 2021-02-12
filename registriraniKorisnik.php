<!DOCTYPE html>
<?php
require 'baza.class.php';
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
            if ($dohvatUloge[0] == 2) {


                echo '<br><br>';
                echo '<form novalidate class="zaForme" action="registriraniKorisnik.php" method="post">';
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


                if (isset($_POST['zahtjevZaBlok'])) {
                    $ID_oglasa = $_POST['zahtjevBlok'];
                    $razlog = $_POST['razlog'];
                    $upit26 = $spojiBazu->selectDB("UPDATE OGLAS SET razlog='$razlog', zahtjev_za_blok='1', vrijeme_zahtjeva=now() WHERE ID_oglasa='$ID_oglasa'");
                    $upit27 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Zahtjev za blokiranje oglasa', now(), 'Korisnik je poslao zahtjev za blokiranje odredenog oglasa')");
                }

                echo '<br><br>';
                $postranici1 = 10;
                if (isset($_GET["page"])) {
                    $stranica1 = intval($_GET["page"]);
                } else {
                    $stranica1 = 1;
                }
                $izracuna = $postranici1 * $stranica1;
                $start1 = $izracuna - $postranici1;
                $upit201 = $spojiBazu->selectDB("SELECT naziv, cijena, vrijeme_trajanja FROM VRSTA_OGLASA LIMIT $start1, $postranici1");

                if (isset($_POST['ASC1'])) {
                    $upit201 = $spojiBazu->selectDB("SELECT naziv, cijena, vrijeme_trajanja FROM VRSTA_OGLASA ORDER BY naziv ASC LIMIT 10");
                } elseif (isset($_POST['DESC1'])) {
                    $upit201 = $spojiBazu->selectDB("SELECT naziv, cijena, vrijeme_trajanja FROM VRSTA_OGLASA ORDER BY naziv DESC LIMIT 10");
                }
                if (isset($_POST['ASCID1'])) {
                    $upit201 = $spojiBazu->selectDB("SELECT naziv, cijena, vrijeme_trajanja FROM VRSTA_OGLASA BY cijena ASC LIMIT 10");
                } elseif (isset($_POST['DESCID1'])) {
                    $upit201 = $spojiBazu->selectDB("SELECT naziv, cijena, vrijeme_trajanja FROM VRSTA_OGLASA ORDER BY cijena DESC LIMIT 10");
                }

                if (isset($_POST['search2'])) {
                    $query = $_POST['query3'];
                    $upit201 = $spojiBazu->selectDB("SELECT naziv, cijena, vrijeme_trajanja FROM VRSTA_OGLASA WHERE naziv LIKE '%$query%' OR cijena LIKE '%$query%' OR vrijeme_trajanja LIKE '%$query%' LIMIT 10");
                }

                echo '<form action="registriraniKorisnik.php" method="post">';
                echo 'Sortiraj po cijeni: &nbsp;&nbsp;&nbsp';
                echo '<input type="submit" name="ASCID1" value="Uzlazno"> &nbsp;&nbsp';
                echo '<input type="submit" name="DESCID1" value="Silazno"><br>';
                echo 'Sortiraj po nazivu: ';
                echo '<input type="submit" name="ASC1" value="Uzlazno"> &nbsp;&nbsp';
                echo '<input type="submit" name="DESC1" value="Silazno"><br>';
                echo '<input class="pretrazivanje" type="text" name="query3">';
                echo '<input type="submit" name="search2" value="Pretraži">';

                if ($upit201->num_rows > 0) {
                    echo '<table><caption>VRSTE OGLASA</caption><tr><th>Vrsta oglasa</th><th>Cijena</th><th>Vrijeme trajanja</th></tr>';
                    while ($row = $upit201->fetch_assoc()) {

                        echo "<tr><td>" . $row["naziv"] . "</td><td>" . $row["cijena"] . "</td><td>" . $row["vrijeme_trajanja"] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
                echo '</form>';
                if (isset($stranica1)) {
                    $upit201 = $spojiBazu->selectDB("SELECT COUNT(*) AS Ukupno FROM VRSTA_OGLASA");
                    if ($upit201->num_rows > 0) {
                        $rs1 = $upit201->fetch_assoc();
                        $ukupno1 = $rs1["Ukupno"];
                        $ukupnoStranica1 = ceil($ukupno1 / $postranici1);
                        if ($stranica1 <= 1) {
                            echo "<span id='poveznica' style='font-weight: bold;'>Prethodna </span>";
                        } else {
                            $a = $stranica1 - 1;
                            echo "<span><a id='poveznica2' href='registriraniKorisnik.php?page=$a'>< Prethodna </a></span>";
                        }
                        for ($b = 1; $b <= $ukupnoStranica1; $b++) {
                            if ($b <> $stranica1) {
                                echo "<span><a id='poveznica2' href='registriraniKorisnik.php?page=$b'>$b</a></span>";
                            } else {
                                echo "<span id='poveznica' style='font-weight: bold;'>$b</span>";
                            }
                        }
                        if ($stranica1 == $ukupnoStranica1) {
                            echo "<span id='poveznica' style='font-weight: bold;'> Sljedeća </span>";
                        } else {
                            $a = $stranica1 + 1;
                            echo "<span><a id='poveznica2' href='registriraniKorisnik.php?page=$a'>Sljedeća ></a></span>";
                        }
                    }
                }

                echo '<br><br>';
                echo '<form enctype="multipart/form-data" class="zaForme" novalidate action="registriraniKorisnik.php" method="post">';
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
                echo 'Odaberite stranicu na kojoj će se prikazati oglas: <br>';
                $upit93 = $spojiBazu->selectDB("SELECT ID_stranica, naziv FROM STRANICA");

                if ($upit93->num_rows > 0) {
                    echo "<select name = 'odabirstr'>";
                    echo '<option value="" selected="selected"></option>';
                    while ($row = $upit93->fetch_assoc()) {
                        echo '<option  value="' . $row['ID_stranica'] . '">' . $row["naziv"] . '</option>';
                    }
                    echo "</select><br>";
                } else {
                    echo '0 results';
                }
                echo 'Odaberite poziciju: <br>';
                $upit94 = $spojiBazu->selectDB("SELECT ID_pozicija, pozicioniranje FROM POZICIJA");

                if ($upit94->num_rows > 0) {
                    echo "<select name = 'odabirpoz'>";
                    echo '<option value="" selected="selected"></option>';
                    while ($row = $upit94->fetch_assoc()) {
                        echo '<option  value="' . $row['ID_pozicija'] . '">' . $row['ID_pozicija'] . " " . $row["pozicioniranje"] . '</option>';
                    }
                    echo "</select><br>";
                } else {
                    echo '0 results';
                }
                echo '<input type="hidden" name="MAX_FILE_SIZE" value="300000">
                Učitajte sliku za oglas: <input name="userfile" type="file"><br>';
                echo '<input type="submit" name="dodajZahtjev" value="Pošalji zahtjev"><br>';
                echo '</form>';
                if (isset($_POST['dodajZahtjev'])) {
                    $idpoz = $_POST['odabirpoz'];
                    $idstr = $_POST['odabirstr'];
                    $ID_vrsta = $_POST['odabirVrste'];
                    $vrijeme_aktivacije = $_POST['vrijemeAktivacije'];
                    $nazivOglasa = $_POST['nazivOglasa'];
                    $webAdresa = $_POST['webadresa'];
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
                        } else {
                            $upit23 = $spojiBazu->selectDB("SELECT ID_korisnika FROM KORISNIK WHERE korisnicko_ime='$korisnikIme'");
                            $ID_kor = $upit23->fetch_array();
                            $upit22 = $spojiBazu->selectDB("INSERT INTO OGLAS VALUES (default, '$nazivOglasa', '$webAdresa', '$userfile_name', '$vrijeme_aktivacije', '0', '0', '0', 'NULL', '0000-00-00 00:00:00', '1', '$ID_vrsta', '$ID_kor[0]', '1', '7')");
                            $upit27 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Zahtjev za kreiranje novog oglasa', now(), 'Korisnik je poslao zahtjev za kreiranje novog oglasa')");
                            $upit95 = $spojiBazu->selectDB("INSERT INTO STRANICA_POZICIJA VALUES (default, '$idpoz', '$idstr')");
                            $upit27 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnikIme', 'Nova pozicija stranica', now(), 'Oglas je dodjeljen na određenoj poziciji i stranici')");
                        }
                    }
                }

                echo '<br><br>';
                echo '<h3>GALERIJA</h3>';
                $upit29 = $spojiBazu->selectDB("SELECT ID_korisnika FROM KORISNIK WHERE korisnicko_ime='$korisnikIme'");
                $IDKorisnika = $upit29->fetch_array();
                $upit28 = $spojiBazu->selectDB("SELECT slika_naziv FROM OGLAS WHERE FK_korisnik='$IDKorisnika[0]'");
                if ($upit28->num_rows > 0) {
                    while ($slikaNaziv = $upit28->fetch_array()) {
                        echo '<img src="multimedija/' . $slikaNaziv[0] . '" alt=' . $slikaNaziv[0] . ' height="300" width="400">&nbsp;&nbsp;';
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
                echo '<form class="zaForme" novalidate action="registriraniKorisnik.php" method="post">';
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
                if (isset($_POST['azuriraj'])) {
                    $IDOdabir = $_POST['azurirajOglas'];
                    $vrAkt = $_POST['vrijemeAktivacije2'];
                    $nazA = $_POST['nazivOglasa2'];
                    $web2 = $_POST['webadresa2'];
                    $upit72 = $spojiBazu->selectDB("UPDATE OGLAS SET naziv='$nazA', web_adresa='$web2', vrijeme_aktivacije='$vrAkt' WHERE ID_oglasa='$IDOdabir'");
                    $upit73 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES(default, '$korisnikIme', 'Azuriranje oglasa', now(), 'Korisnik je azurirao jedan od oglasa u pripremi')");
                }

                echo '<br><br>';
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
            echo '<form action="registriraniKorisnik.php" method="post">';
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
                
                $postranici = 10;
                if (isset($_GET["page"])) {
                    $stranica = intval($_GET["page"]);
                } else {
                    $stranica = 1;
                }
                $izracun = $postranici * $stranica;
                $start = $izracun - $postranici;
                $upit20 = $spojiBazu->selectDB("SELECT * FROM LOKACIJA LIMIT $start, $postranici");

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
                
                echo '<form action="registriraniKorisnik.php" method="post">';
                echo '<fieldset>';
                echo '<legend>NEREGISTRIRANI:</legend>';
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

                echo '</fieldset>';
                echo '</form>';

                if (isset($stranica)) {
                    $upit20 = $spojiBazu->selectDB("SELECT COUNT(*) AS Ukupno FROM LOKACIJA");
                    if ($upit20->num_rows > 0) {
                        $rs = $upit20->fetch_assoc();
                        $ukupno = $rs["Ukupno"];
                        $ukupnoStranica = ceil($ukupno / $postranici);
                        if ($stranica <= 1) {
                            echo "<span id='poveznica' style='font-weight: bold;'>Prethodna </span>";
                        } else {
                            $j = $stranica - 1;
                            echo "<span><a id='poveznica2' href='registriraniKorisnik.php?page=$j'>< Prethodna </a></span>";
                        }
                        for ($i = 1; $i <= $ukupnoStranica; $i++) {
                            if ($i <> $stranica) {
                                echo "<span><a id='poveznica2' href='registriraniKorisnik.php?page=$i'>$i</a></span>";
                            } else {
                                echo "<span id='poveznica' style='font-weight: bold;'>$i</span>";
                            }
                        }
                        if ($stranica == $ukupnoStranica) {
                            echo "<span id='poveznica' style='font-weight: bold;'> Sljedeća </span>";
                        } else {
                            $j = $stranica + 1;
                            echo "<span><a id='poveznica2' href='registriraniKorisnik.php?page=$j'>Sljedeća ></a></span>";
                        }
                    }
                }
                echo '<br><br>';
                
                
            } else {
                header('Location: pocetna.php');
            }

            $spojiBazu->zatvoriDB();
            ?>
        </section>

        <footer>
            <p class='c'> &copy; 24 M.Mačinković</p>
        </footer>


        <script src="js/mmacinkov.js" type="text/javascript"></script>
    </body>
</html>
