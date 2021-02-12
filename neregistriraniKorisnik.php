<!DOCTYPE html>
<?php
require 'baza.class.php';
if (isset($_COOKIE['kolacic'])) {
    
} else {
    echo '<script language="javascript">';
    echo 'alert("Prihvatite uvjete korištenja!")';
    echo '</script>';
    setcookie('kolacic', 'korisnik', time() + 48 * 3600);
}
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
        <?php
        $spojiBazu = new Baza;
        $spojiBazu->spojiDB();
        $upit106 = $spojiBazu->selectDB("SELECT o.ID_oglasa, o.slika_naziv, o.web_adresa FROM OGLAS o JOIN STRANICA_POZICIJA sp ON o.FK_stripoz = sp.ID_strpoz JOIN VRSTA_OGLASA vo ON o.FK_vrsta_oglasa = vo.ID_vrsta WHERE o.FK_status =  '2' AND sp.FK_pozicija =  '3' AND sp.FK_stranica =  '3'");
            if ($upit106->num_rows > 0) {
                while ($slika = $upit106->fetch_array()) {
                    $upit107 = $spojiBazu->selectDB("SELECT visina, sirina FROM POZICIJA WHERE ID_pozicija='3'");
                    $dimenzije = $upit107->fetch_array();
                    echo '
            <a href="'.$slika[2].'">
            <img onclick=brojiKlikove('. $slika[0] .') class="center" src="multimedija/' . $slika[1] . '" width='.$dimenzije[1].' height='.$dimenzije[0].'  alt="slika.png">
            </a>';
                }
            } else {
                echo'<img src="multimedija/prazna.png" width="350" height="250"  alt="slika.png">';
            }
            
        ?>
        <section>
            <?php
            
            $spojiBazu->spojiDB();
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

            echo '<form action="neregistriraniKorisnik.php" method="post">';
            echo 'Sortiraj po ID-u: &nbsp;&nbsp;&nbsp';
            echo '<input type="submit" name="ASCID" value="Uzlazno"> &nbsp;&nbsp';
            echo '<input type="submit" name="DESCID" value="Silazno"><br>';
            echo 'Sortiraj po nazivu: ';
            echo '<input type="submit" name="ASC" value="Uzlazno"> &nbsp;&nbsp';
            echo '<input type="submit" name="DESC" value="Silazno"><br>';
            echo '<input class="pretrazivanje" type="text" name="query">';
            echo '<input type="submit" name="search" value="Pretraži">';
            
            $upit108 = $spojiBazu->selectDB("SELECT o.ID_oglasa, o.slika_naziv, o.web_adresa FROM OGLAS o JOIN STRANICA_POZICIJA sp ON o.FK_stripoz = sp.ID_strpoz JOIN VRSTA_OGLASA vo ON o.FK_vrsta_oglasa = vo.ID_vrsta WHERE o.FK_status =  '2' AND sp.FK_pozicija =  '6' AND sp.FK_stranica =  '3'");
            if ($upit108->num_rows > 0) {
                while ($slika = $upit108->fetch_array()) {
                    $upit107 = $spojiBazu->selectDB("SELECT visina, sirina FROM POZICIJA WHERE ID_pozicija='6'");
                    $dimenzije = $upit107->fetch_array();
                    echo '
            <a href="'.$slika[2].'">
            <img onclick=brojiKlikove('. $slika[0] .') class="desno" src="multimedija/' . $slika[1] . '" width='.$dimenzije[1].' height='.$dimenzije[0].'  alt="slika.png">
            </a>';
                }
            } else {
                echo'<img class="desno" src="multimedija/prazna.png" width="350" height="250"  alt="slika.png">';
            }
            
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
                    $ukupnoStranica = ceil($ukupno / $postranici);
                    if ($stranica <= 1) {
                        echo "<span id='poveznica' style='font-weight: bold;'>Prethodna </span>";
                    } else {
                        $j = $stranica - 1;
                        echo "<span><a id='poveznica2' href='neregistriraniKorisnik.php?page=$j'>< Prethodna </a></span>";
                    }
                    for ($i = 1; $i <= $ukupnoStranica; $i++) {
                        if ($i <> $stranica) {
                            echo "<span><a id='poveznica2' href='neregistriraniKorisnik.php?page=$i'>$i</a></span>";
                        } else {
                            echo "<span id='poveznica' style='font-weight: bold;'>$i</span>";
                        }
                    }
                    if ($stranica == $ukupnoStranica) {
                        echo "<span id='poveznica' style='font-weight: bold;'> Sljedeća </span>";
                    } else {
                        $j = $stranica + 1;
                        echo "<span><a id='poveznica2' href='neregistriraniKorisnik.php?page=$j'>Sljedeća ></a></span>";
                    }
                }
            }
            
            
            $spojiBazu->zatvoriDB();
            ?>


        </section>
        <div style="height: 60px">
        </div>
        <footer>
            <p> &copy; 24 M.Mačinković</p>
        </footer>


        <script src="js/mmacinkov.js" type="text/javascript"></script>
    </body>
</html>

