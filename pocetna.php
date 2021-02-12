<!DOCTYPE html>
<?php
require 'baza.class.php';
$spojiBazu = new Baza();
$spojiBazu->spojiDB();
session_start();
$korisnickoIme = $_SESSION['korisnik'];
if($_SESSION['korisnik']){
   $upit18 = $spojiBazu->selectDB("SELECT FK_uloga FROM KORISNIK WHERE korisnicko_ime ='$korisnickoIme'"); 
                    $dohvatUloge = $upit18->fetch_array(); 
                    if ($dohvatUloge[0] == 1){
                        header('Location: neregistriraniKorisnik.php');
                    }
                    elseif ($dohvatUloge[0] == 2){
                        header('Location: registriraniKorisnik.php');
                    }
                    elseif ($dohvatUloge[0] == 3) {
                    header('Location: moderator.php');
                }
                   elseif ($dohvatUloge[0] == 4){
                       header('Location: administrator.php');
                   } 
}
 else {
    header('Location: neregistriraniKorisnik.php');


$spojiBazu->zatvoriDB();
 }
?>
<html lang="hr">
    <head>
        <title>POSLOVNI PROSTOR</title>
        <meta charset="utf-8">
        <link href="css/mmacinkov.css" rel="stylesheet" type="text/css" >
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
            <footer>
            <p class='c'> &copy; 24 M.Mačinković</p>
        </footer>


        <script src="js/mmacinkov.js" type="text/javascript"></script>
    </body>
</html>

