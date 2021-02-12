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
            if (!empty($_GET['id'])) {
                $ID_Lokacije = $_GET['id'];
            }

            echo '<form action="izlistajDvorane.php?id=' . $ID_Lokacije . '" method="post">';
            echo '<input type="text" name="query1">';
            echo '<input type="submit" name="search1" value="Pretraži">';
            echo '</form>';

            $upit23 = $spojiBazu->selectDB("SELECT d.ID_dvorane, d.naziv, d.namjena, d.broj_mjesta, zd.status, l.ID_lokacije FROM DVORANA d JOIN ZAUZETOST_DVORANE zd ON d.ID_dvorane = zd.FK_dvorana JOIN LOKACIJA l ON d.FK_lokacija = l.ID_lokacije WHERE zd.status = 0 AND d.FK_lokacija=$ID_Lokacije GROUP BY d.naziv");
            if (isset($_POST['search1'])) {
                $query = $_POST['query1'];

                $upit23 = $spojiBazu->selectDB("SELECT d.ID_dvorane, d.naziv, d.namjena, d.broj_mjesta, zd.status, l.ID_lokacije FROM DVORANA d JOIN ZAUZETOST_DVORANE zd JOIN LOKACIJA l ON d.ID_dvorane = zd.FK_dvorana WHERE zd.status = 0 AND d.FK_lokacija=$ID_Lokacije AND d.namjena LIKE '%$query%' GROUP BY d.naziv");
            }
            if ($upit23->num_rows > 0) {
                echo '<table><caption>DVORANE</caption><tr><th>Naziv dvorane</th><th>Namjena</th><th>Ukupan broj mjesta</th><th>Status</th></tr>';
                while ($row = $upit23->fetch_assoc()) {
                    if ($row["status"] == 0) {
                        $row["status"] = 'Slobodna';
                    }
                    /* else if($row["status"] == 1){
                      $row["status"] = 'Zauzeta';
                      } */
                    echo "<tr><td><a href='izlistajPovratneInformacije.php?idPov=". $row['ID_dvorane'] ."&id=". $row['ID_lokacije'] ."'>" . $row["naziv"] . "</a></td><td>" . $row["namjena"] . "</td><td>" . $row["broj_mjesta"] . "</td><td>" . $row["status"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
            
            
            $spojiBazu->zatvoriDB();
            ?>
        </section>
        <div style="height: 275px">
            </div>
        <footer>
            <p> &copy; 24 M.Mačinković</p>
        </footer>


        <script src="js/mmacinkov.js" type="text/javascript"></script>
    </body>
</html>