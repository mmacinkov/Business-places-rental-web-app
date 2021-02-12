<!DOCTYPE html>
<?php
if ($_SERVER["HTTPS"] != "on") {

    header("Location: https://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x096/prijava.php");
    exit();
}
require 'baza.class.php';
$spojiBazu = new Baza();
$spojiBazu->spojiDB();
$pogreskePrijava = "";
$kolacicPrijava = "";
if (isset($_COOKIE['kolacicPrijava'])) {
    $kolacicPrijava = $_COOKIE['kolacicPrijava'];
}
if (isset($_POST['submit2'])) {
    $korisnickoIme = $_POST['korime1'];
    $upit9 = $spojiBazu->selectDB("SELECT korisnicko_ime FROM KORISNIK WHERE korisnicko_ime ='$korisnickoIme'");
    $dohvatKorisnika = $upit9->fetch_array();
    if ($dohvatKorisnika[0] == $korisnickoIme) {
        $upit = $spojiBazu->selectDB("SELECT aktivni FROM KORISNIK WHERE korisnicko_ime ='$korisnickoIme'");
        $dohvati = $upit->fetch_array();
        if ($dohvati[0] == 1) {
            $upit2 = $spojiBazu->selectDB("SELECT lozinka FROM KORISNIK WHERE korisnicko_ime ='$korisnickoIme'");
            $lozinkaPrijava = $_POST['lozinka'];
            $dohvatLozinke = $upit2->fetch_array();
            if ($dohvatLozinke[0] == $lozinkaPrijava) {
                $upit5 = $spojiBazu->selectDB("SELECT blokiran FROM KORISNIK WHERE korisnicko_ime ='$korisnickoIme'");
                $dohvatBlokiranih = $upit5->fetch_array();
                if ($dohvatBlokiranih[0] == 0) {
                    session_start();
                    $_SESSION['korisnik'] = $korisnickoIme;
                    $upit12 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnickoIme', 'Prijava korisnika', now(), 'Korisnik se prijavio')");
                    setcookie('kolacicPrijava', $korisnickoIme);
                    $upit16 = $spojiBazu->updateDB("UPDATE KORISNIK SET broj_pogresnih_unosa = '0' WHERE korisnicko_ime ='$korisnickoIme'");
                    $upit17 = $spojiBazu->selectDB("SELECT FK_uloga FROM KORISNIK WHERE korisnicko_ime ='$korisnickoIme'");
                    $dohvatUloge = $upit17->fetch_array();
                    if ($dohvatUloge[0] == 1) {
                        header('Location: neregistriraniKorisnik.php');
                    } elseif ($dohvatUloge[0] == 2) {
                        header('Location: registriraniKorisnik.php');
                    } elseif ($dohvatUloge[0] == 3) {
                        header('Location: moderator.php');
                    } elseif ($dohvatUloge[0] == 4) {
                        header('Location: administrator.php');
                    }
                } else {
                    $pogreskePrijava .= "Korisnicki racun je blokiran!";
                }
            } else {
                $upit6 = $spojiBazu->selectDB("SELECT broj_pogresnih_unosa FROM KORISNIK WHERE korisnicko_ime ='$korisnickoIme'");
                $dohvatPogresnih = $upit6->fetch_array();

                $dohvatPogresnih[0] ++;

                if ($dohvatPogresnih[0] < 3) {
                    $pogreskePrijava .= "Unjeli ste krivu lozinku - Imate samo 3 pokušaja!";
                    $upit7 = $spojiBazu->selectDB("UPDATE KORISNIK SET broj_pogresnih_unosa = '$dohvatPogresnih[0]' WHERE korisnicko_ime='$korisnickoIme'");
                    $upit10 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnickoIme', 'Kriva lozinka', now(), 'Unesena je kriva lozinka')");
                } else if ($dohvatPogresnih[0] == 3) {
                    $pogreskePrijava .= "Vaš račun je blokiran jer ste unjeli 3 puta zaredom krivu lozinku!";
                    $upit3 = $spojiBazu->updateDB("UPDATE KORISNIK SET blokiran ='1' WHERE korisnicko_ime ='$korisnickoIme'");
                    $upit4 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnickoIme', 'Blokiranje korisnika', now(), 'Korisnik je blokiran!')");
                    $upit8 = $spojiBazu->updateDB("UPDATE KORISNIK SET broj_pogresnih_unosa = '$dohvatPogresnih[0]' WHERE korisnicko_ime='$korisnickoIme'");
                    $upit11 = $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnickoIme', '3 pogrešna unosa lozinke!', now(), 'Korisnik je 3 puta zaredom unio pogrešnu lozinku!')");
                }
            }
        } else {
            $pogreskePrijava .= "Korisnicko ime nije aktivirano";
        }
    } else {
        $pogreskePrijava .= "Korisnicko ime ne postoji u bazi.";
    }
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
            <?php
            $upit96 = $spojiBazu->selectDB("SELECT o.ID_oglasa, o.slika_naziv, o.web_adresa FROM OGLAS o JOIN STRANICA_POZICIJA sp ON o.FK_stripoz = sp.ID_strpoz JOIN VRSTA_OGLASA vo ON o.FK_vrsta_oglasa = vo.ID_vrsta WHERE o.FK_status =  '2' AND sp.FK_pozicija =  '1' AND sp.FK_stranica =  '1'");
            if ($upit96->num_rows > 0) {
                while ($slika = $upit96->fetch_array()) {
                    $upit97 = $spojiBazu->selectDB("SELECT visina, sirina FROM POZICIJA WHERE ID_pozicija='1'");
                    $dimenzije = $upit97->fetch_array();
                    echo '
            <a onclick=brojiKlikove('. $slika[0] .') href="'.$slika[2].'">
            <img src="multimedija/' . $slika[1] . '" width='.$dimenzije[1].' height='.$dimenzije[0].'  alt="slika.png">
            </a>';
                }
            } else {
                echo'<img src="multimedija/prazna.png" width="350" height="250"  alt="slika.png">';
            }
            $upit98 = $spojiBazu->selectDB("SELECT ID_oglasa, o.slika_naziv, o.web_adresa FROM OGLAS o JOIN STRANICA_POZICIJA sp ON o.FK_stripoz = sp.ID_strpoz JOIN VRSTA_OGLASA vo ON o.FK_vrsta_oglasa = vo.ID_vrsta WHERE o.FK_status =  '2' AND sp.FK_pozicija =  '7' AND sp.FK_stranica =  '1'");
            if ($upit98->num_rows > 0) {
                while ($slika = $upit98->fetch_array()) {
                    $upit99 = $spojiBazu->selectDB("SELECT visina, sirina FROM POZICIJA WHERE ID_pozicija='7'");
                    $dimenzije = $upit99->fetch_array();
                    echo '
            <a onclick=brojiKlikove('. $slika[0] .') href="'.$slika[2].'">
            <img class="desno" src="multimedija/' . $slika[1] . '" width='.$dimenzije[1].' height='.$dimenzije[0].'  alt="slika.png">
            </a>';
                }
            } else {
               echo'<img src="multimedija/prazna.png" width="350" height="250"  alt="slika.png">';
            }
            
            
                ?>
                <div style="height: 50px"></div> 
                <form novalidate id="prijava" method="post" name="form" action="prijava.php">
                    <fieldset>
                        <legend>PRIJAVA</legend>
                        <p><label>Korisničko ime: 
                                <input class="sirina" type="text" id="korime1" name="korime1" maxlength="20" placeholder="korisničko ime" value="<?php echo $kolacicPrijava; ?>" required="required"></label><br>
                            <label>Lozinka: 
                                <input class="sirina" type="password" name="lozinka" placeholder="lozinka" required="required"></label><br>
                            <a href="zaboraviliSteLozinku.php">Zaboravili ste lozinku?</a>    
                            <input name="submit2" type="submit" value=" Prijavi se ">

                            <?php
                            echo $pogreskePrijava;
                            ?>
                </fieldset>
            </form>
        </section>
        <div style="height: 55px">
        </div>
        <?php
        $upit100 = $spojiBazu->selectDB("SELECT o.ID_oglasa, o.slika_naziv, o.web_adresa FROM OGLAS o JOIN STRANICA_POZICIJA sp ON o.FK_stripoz = sp.ID_strpoz JOIN VRSTA_OGLASA vo ON o.FK_vrsta_oglasa = vo.ID_vrsta WHERE o.FK_status =  '2' AND sp.FK_pozicija =  '4' AND sp.FK_stranica =  '1'");
            if ($upit100->num_rows > 0) {
                while ($slika = $upit100->fetch_array()) {
                    $upit101 = $spojiBazu->selectDB("SELECT visina, sirina FROM POZICIJA WHERE ID_pozicija='4'");
                    $dimenzije = $upit101->fetch_array();
                    echo '
            <a onclick=brojiKlikove('. $slika[0] .') href="'.$slika[2].'">
            <img class="center" src="multimedija/' . $slika[1] . '" width='.$dimenzije[1].' height='.$dimenzije[0].'  alt="slika.png">
            </a>';
                }
            } else {
                echo'<img src="multimedija/prazna.png" width="350" height="250"  alt="slika.png">';
            }
            $spojiBazu->zatvoriDB();
        ?>
        <footer>
            <p> &copy; 24 M.Mačinković</p>
        </footer>


        <script src="js/mmacinkov.js" type="text/javascript"></script>
    </body>

</html>