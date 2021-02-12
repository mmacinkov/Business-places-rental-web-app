<!DOCTYPE html>
<?php
require 'baza.class.php';
?>
<html lang = "hr">
    <head>
        <title>POSLOVNI PROSTOR</title>
        <meta charset = "utf-8">
        <link href = "css/mmacinkov2.css" rel = "stylesheet" type = "text/css" >
        <link href = "css/mmacinkov_prilagodbe.css" rel = "stylesheet" type = "text/css" >
        <link rel = "stylesheet" href = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
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
            $spojiBazu = new Baza();
            $spojiBazu->spojiDB();
            if (!empty($_GET['idPov'])) {
                $ID_Dvorane = $_GET['idPov'];
            }

            if (!empty($_GET['id'])) {
                $ID_Lokacije = $_GET['id'];
            }


            $upit24 = $spojiBazu->selectDB("SELECT d.naziv,  d.namjena, pi.ocjena, pi.opis, zd.status FROM DVORANA d JOIN POVRATNA_INFORMACIJA pi ON d.ID_dvorane=pi.FK_dvorana JOIN ZAUZETOST_DVORANE zd ON  d.ID_dvorane = zd.FK_dvorana WHERE zd.status = 0 AND d.FK_lokacija=$ID_Lokacije AND pi.FK_dvorana = $ID_Dvorane GROUP BY d.naziv");
            if ($upit24->num_rows > 0) {
                echo '<table><caption>POVRATNE INFORMACIJE</caption><tr><th>Naziv dvorane</th><th>Namjena</th><th>Ocjena</th><th>Komentar</th><th>Status</th></tr>';
                while ($row = $upit24->fetch_assoc()) {
                    if ($row["status"] == 0) {
                        $row["status"] = 'Slobodna';
                    }
                    /* else if($row["status"] == 1){
                      $row["status"] = 'Zauzeta';
                      } */
                    echo "<tr><td>" . $row["naziv"] . "</td><td>" . $row["namjena"] . "</td><td>" . $row["ocjena"] . "</td><td>" . $row["opis"] . "</td><td>" . $row["status"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }

            echo '<form class="zaForme" action="izlistajPovratneInformacije.php?idPov='.$ID_Dvorane.'&id='.$ID_Lokacije.' " method="post">';
            echo '<h3>REZERVACIJA TERMINA</h3>';
            echo 'Odaberite termin: ';
            $upit25 = $spojiBazu->selectDB("SELECT t.ID_termina, t.rok_prijave, t.vrijeme_pocetka, t.vrijeme_zavrsetka FROM DVORANA d JOIN POVRATNA_INFORMACIJA pi ON d.ID_dvorane=pi.FK_dvorana JOIN ZAUZETOST_DVORANE zd ON  d.ID_dvorane = zd.FK_dvorana JOIN TERMIN t ON t.ID_termina=zd.FK_termin WHERE zd.status = 0 AND d.FK_lokacija=$ID_Lokacije AND pi.FK_dvorana = $ID_Dvorane");
            if ($upit25->num_rows > 0) {
                echo "<select name = 'naziv12' required='required'>";
                echo '<option value="" selected="selected"></option>';
                while ($row = $upit25->fetch_assoc()) {
                    echo '<option  value="' . $row['ID_termina'] . '">' . $row['rok_prijave'] . "&nbsp;Pocetak: " . $row['vrijeme_pocetka'] . "&nbsp;Kraj: " . $row['vrijeme_zavrsetka'] . '</option>';
                }
                echo "</select><br>";
            } else {
                echo '0 results';
            }
            echo 'Unesite mail na koji ćete dobiti potvrdu i kod za ostavljanje povratne informacije: ';
            echo '<input type="text" name="mail" required="required"><br>';
            echo '<input type="submit" name="odaberiTermin" value="Odaberi termin"> &nbsp';
            echo '</form>';
            
            if (isset($_POST['odaberiTermin'])) {
                $ID_Termina = $_POST['naziv12'];
                $upit26 = $spojiBazu->updateDB("UPDATE ZAUZETOST_DVORANE SET status='1' WHERE FK_termin='$ID_Termina' AND FK_dvorana='$ID_Dvorane'");
                $upit27 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, 'NULL', 'Rezervacija termina', now(), 'Rezerviranje termina te dobijanje potvrdnog maila s kodom za ostavljanje povratne informacije')");
                function kod() {
                    $izbor = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                    $kod = array();
                    $duljina = strlen($izbor) - 1;
                    for ($i = 0; $i < 10; $i++) {
                        $n = rand(0, $duljina);
                        $kod[] = $izbor[$n];
                    }
                    return implode($kod);
                }

                $generirajKod = kod();
                $mail = $_POST['mail'];
                $naslov = "Kod za povratnu informaciju";
                $poruka = "
Rezervirali ste termin! 
Kod za ostavljanje povratne informacije: 
------------------------
Kod: $generirajKod
------------------------
";
                mail($mail, $naslov, $poruka);
                $upit28 = $spojiBazu->selectDB("INSERT INTO KORISNIK VALUES(default, 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '$mail', 'NULL', '1', 'NULL', now(), 'NULL', 'NULL')");
                $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, 'NULL', 'Dodavanje korisnika', now(), 'Dodavanje novog korisnika u bazu')");
            }
            echo '<br><br>';
            echo '<form class="zaForme" action="izlistajPovratneInformacije.php?idPov='.$ID_Dvorane.'&id='.$ID_Lokacije.' " method="post">'; 
            echo '<h3>POVRATNA INFORMACIJA</h3>';
            echo 'Nakon što je završio Vaš termin možete ostaviti povratnu informaciju: <br>';
            echo 'Vaš kod: <br>';
            echo '<input type="text" name="kod"><br>';
            echo 'Ocjena (1-5): <br>'; 
            echo '<input type="number" name="ocjenaTermina" min="1" max="5"><br>';
            echo 'Komentar: <br>';
            echo '<input type="text" name="komentar"><br>';
            echo '<input type="submit" name="inputZaPovratnu" value=" Unesi ">';
            echo '</form>';
            
            if(isset($_POST['inputZaPovratnu'])){
                $kod = $_POST['kod']; 
                $ocjena = $_POST['ocjenaTermina']; 
                $komentar = $_POST['komentar']; 
                    $upit30 = $spojiBazu->selectDB("SELECT ID_korisnika FROM KORISNIK ORDER BY ID_korisnika DESC LIMIT 1");
                    $ID_Korisnika = $upit30->fetch_array();
                    $upit29 = $spojiBazu->selectDB("INSERT INTO POVRATNA_INFORMACIJA VALUES ('$ID_Korisnika[0]', '$ID_Dvorane', '$kod', '$ocjena', '$komentar')");                     
                    $upit36 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, 'NULL', 'Nova povratna informacija', now(), 'Unešena je nova povratna informacija')");
                }
            
            $spojiBazu->zatvoriDB();
            ?>
        </section>
        <div style="height: 50px">
            </div>
        <footer>
            <p> &copy; 24 M.Mačinković</p>
        </footer>


        <script src="js/mmacinkov.js" type="text/javascript"></script>
    </body>
</html>