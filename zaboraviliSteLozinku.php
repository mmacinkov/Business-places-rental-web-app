<!DOCTYPE html>
<?php
require 'baza.class.php';
$spojiBazu = new Baza;
$spojiBazu->spojiDB();
$pogreske = "";
if (isset($_POST['submit3'])) {
    $korisIme = $_POST['korisnickoime'];
    $upit13 = $spojiBazu->selectDB("SELECT korisnicko_ime, email FROM KORISNIK WHERE korisnicko_ime = '$korisIme'");
    $uzmi = $upit13->fetch_array();
    if ($uzmi[0] != $korisIme) {
        $pogreske .= "Pogrešan unos korisničkog imena.";
    } else {

        function generirajLozinku() {
            $izbor = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $lozinka = array();
            $duljina = strlen($izbor) - 1;
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $duljina);
                $lozinka[] = $izbor[$n];
            }
            return implode($lozinka);
        }

        $dobivenaLozinka = generirajLozinku();
        $email = $_POST['mail'];
        $prima = $email;
        $naslov = "Nova lozinka";
        $poruka = "
Vasi novi podaci su: 
------------------------
Korisnicko ime: $korisIme
Lozinka: $dobivenaLozinka
------------------------
Kliknite na link i vratite se na prijavu:
http://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x096/prijava.php
";
        mail($prima, $naslov, $poruka);
        
        $upit14 = $spojiBazu->selectDB("UPDATE KORISNIK SET lozinka = '$dobivenaLozinka' WHERE korisnicko_ime = '$korisIme'");
        $upit15 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisIme', 'Nova lozinka', now(), 'Dobijanje nove lozinke te njeno ažuriranje u bazi')");
        header("Location: prijava.php");
            }
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
            <div style="height: 50px"></div> 
            <form id="zaborav" method="post" name="zaborav" action="zaboraviliSteLozinku.php">
                <fieldset>
                    <legend>ZABORAVILI STE LOZINKU</legend>
                    <p><label for="korisnickoime">Korisničko ime: </label><br> 
                <input type="text" name="korisnickoime" id="korisnickoime" placeholder="Korisničko ime" required="required"><br>
                <label for="mail">Unesite e-mail na koji želite da Vam pošaljemo novu lozinku: </label><br>
                <input type="email" id="mail" name="mail" placeholder="ime.prezime@posluzitelj.xxx" required="required"><br>
                <input name="submit3" type="submit" value=" Pošalji mi e-mail ">
                </fieldset>
                <?php echo $pogreske; ?>
            </form>
        </section>
        <div style="height: 55px">
            </div>
        <footer>
            <p> &copy; 24 M.Mačinković</p>
        </footer>


        <script src="js/mmacinkov.js" type="text/javascript"></script>
    </body>

</html> 