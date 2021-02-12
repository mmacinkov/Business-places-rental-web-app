<!DOCTYPE html>
<?php
require 'baza.class.php';
$pogreskeEmail = "";
$pogreskeDatum = "";
$pogreskePotvrdaLozinke = "";
$pogreskeKorIme = "";
$pogreskeGeneralno = "";
$pogreskePrezime = "";
$pogreskeIme = "";
$pogreskeLozinka = "";
$kraj = "";

if (isset($_POST['submit1'])) {
    $email = $_POST['email'];
    $regexEmail = '/^[a-zA-Z0-9]+[.]?[a-zA-Z0-9]+@[a-zA-Z0-9]+[.]{1}[a-zA-Z]{2,}$/';
    if (strlen($email) <= 10) {
        $pogreskeEmail .= "GREŠKA! Email ima premalo znakova!";
    } else if (strlen($email) >= 30) {
        $pogreskeEmail .= "GREŠKA! Email ima previše znakova!";
    } else if (!(preg_match($regexEmail, $email))) {
        $pogreskeEmail .= "GREŠKA! Uneseni email nije ispravan!";
    }

    $datum = $_POST['datum'];
    $najmanjiDatum = "01/01/2000";
    if (strtotime($datum) > strtotime($najmanjiDatum)) {
        $pogreskeDatum .= "GREŠKA! Morate biti rođeni prije 01.01.2000. da bi se mogli registrirati!";
    }

    $lozinka1 = $_POST['lozinka1'];
    $lozinka2 = $_POST['lozinka2'];
    if ($lozinka2 != $lozinka1) {
        $pogreskePotvrdaLozinke = "GREŠKA! Potvrdna lozinka i lozinka se ne poklapaju!";
    }

    $korime = $_POST['korime'];
    $velikoSlovo = strtoupper($korime{0});
    if (strlen($korime) < 5) {
        $pogreskeKorIme = "GREŠKA! Korisničko ime mora imati bar 5 znakova!";
    } else if ($velikoSlovo != $korime{0}) {
        $pogreskeKorIme = "GREŠKA! Korisničko ime mora početi s velikim slovom!";
    }

    foreach ($_POST as $k => $v) {
        if (empty($v)) {
            $pogreskeGeneralno = "GREŠKA! Ispunite sva polja";
        } else if (strlen($v) > 0) {
            if (strpos($v, '!')) {
                $pogreskeGeneralno = 'GREŠKA! Pokušavate unijeti nedozvoljeni znak "!"!';
            } else if (strpos($v, '#')) {
                $pogreskeGeneralno = 'GREŠKA! Pokušavate unijeti nedozvoljeni znak "#"!';
            } else if (strpos($v, '?')) {
                $pogreskeGeneralno = 'GREŠKA! Pokušavate unijeti nedozvoljeni znak "?"!';
            } else if (strpos($v, '\'')) {
                $pogreskeGeneralno = 'GREŠKA! Pokušavate unijeti nedozvoljeni znak "\"!';
            }
        }
    }

    $prezime = $_POST['prez'];
    $regexPrezime = '/^[A-Z][A-Za-z]+$/';
    if (!(preg_match($regexPrezime, $prezime))) {
        $pogreskePrezime = "GREŠKA! Prezime se mora sastojati samo od slova(bar 2) te prvo slovo mora biti veliko!";
    }

    $ime = $_POST['ime'];
    $regexIme = '/^[A-Z][A-Za-z]+$/';
    if (!(preg_match($regexIme, $ime))) {
        $pogreskeIme = "GREŠKA! Ime se mora sastojati samo od slova(bar 2) te prvo slovo mora biti veliko!";
    }

    $lozinka = $_POST['lozinka1'];
    $regexLozinka = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';
    if (strlen($lozinka) < 8) {
        $pogreskeLozinka = "GREŠKA! Lozinka se mora sastojati od najmanje 8 znakova!";
    } else if (!(preg_match($regexLozinka, $lozinka))) {
        $pogreskeLozinka = "GREŠKA! Unesena lozinka nije ispravnog formata(bar jedno veliko slovo, jedno malo slovo, jedna brojka)";
    }

    $kraj = "Uspješno ste se registrirali, molimo potvrdite Vaš račun odlaskom na Vaš mail gdje Vam je poslana poruka.";

    $sol = sha1(time());
    $kriptiranaL = sha1($sol . "-" . $lozinka);

    $spol = $_POST['spol'];
    $spojiBazu = new Baza;
    $spojiBazu->spojiDB();
    $sql = $spojiBazu->selectDB("INSERT INTO KORISNIK VALUES(default, '$korime', '$lozinka', '$kriptiranaL', '$ime', '$prezime', '$datum', '$email', '$spol', '2', '0', now(), '0', '0')");
    $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korime', 'Dodavanje korisnika', now(), 'Dodavanje novog korisnika u bazu')");

    $prima = $email;
    $naslov = 'Registracija | Verifikacija';
    $poruka = "
 
Hvala na prijavi!
Vas racun je kreiran, mozete se prijaviti sa Vasim podacima koji su navedeni, nakon sto aktivirate Vas racun klikom na link ispod.
 
------------------------
Korisnicko ime: $korime
Lozinka: $lozinka1
------------------------
 
Kliknite na link i aktivirajte Vas racun:
http://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x096/aktivacijaEmaila.php?kriptiranaL=$kriptiranaL";

    $headers = 'From:noreply@barka.foi.hr';
    mail($prima, $naslov, $poruka, $headers);
    
    $spojiBazu->zatvoriDB();
}
?>

<html lang="hr">
<?php
echo $kraj;
?>
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
      
            <div style="height: 75px"></div>
            <form novalidate id="registracija" method="post" name="forma" action="registracija.php">
                <fieldset>
                    <legend>REGISTRACIJA</legend>
                    <p>
                        <label for="korime">Korisničko ime: </label>
                        <input type="text" id="korime" name="korime" maxlength="20" placeholder="korisničko ime"  required="required">
<?php
echo $pogreskeKorIme;
?>
                        <br>
                        <label for="lozinka1">Lozinka: </label>
                        <input type="password" id="lozinka1" name="lozinka1" placeholder="lozinka" required="required">
<?php
echo $pogreskeLozinka;
?>
                        <br>
                        <label for="lozinka2">Potvrdi lozinku: </label>
                        <input type="password" id="lozinka2" name="lozinka2" placeholder="lozinka" required="required">
                        <?php
                        echo $pogreskePotvrdaLozinke;
                        ?>
                        <br>
                        <label for="email">Email adresa: </label>
                        <input type="email" id="email" name="email" placeholder="ime.prezime@posluzitelj.xxx" required="required">
                        <?php
                        echo $pogreskeEmail;
                        ?> <br>
                        <label for="ime">Ime: </label>
                        <input type="text" id="ime" name="ime" size="20" maxlength="30" placeholder="Ime">
                        <?php
                        echo $pogreskeIme;
                        ?>
                        <br>
                        <label for="prez">Prezime: </label>
                        <input type="text" id="prez" name="prez" size="20" maxlength="50" placeholder="Prezime">
                        <?php
                        echo $pogreskePrezime;
                        ?>
                        <br>
                        <label for="datum">Datum rođenja: </label>
                        <input type="date" id="datum" name="datum">
                        <?php
                        echo $pogreskeDatum;
                        ?> <br>
                        <label for="spol">Spol: </label>
                        <select id="spol" name="spol">
                            <option value="" selected="selected"></option>
                            <option value="muško">muško</option>
                            <option value="žensko">žensko</option> 
                        </select>    
                        <br> 
                    <div class="g-recaptcha" data-sitekey="6Le2SlwUAAAAAJWIIfN9rr9sw9QiCv9q8xY83D1Y"></div><br>
                    <input name="submit1" id="submit1" type="submit" value=" Registriraj se ">
                </fieldset>
                        <?php
                        echo $pogreskeGeneralno;
                        ?>

            </form>    

        </section>
        <div style="height: 55px">
            </div>
        <?php
        $spojiBazu = new Baza;
    $spojiBazu->spojiDB();
        $upit102 = $spojiBazu->selectDB("SELECT o.ID_oglasa, o.slika_naziv, o.web_adresa FROM OGLAS o JOIN STRANICA_POZICIJA sp ON o.FK_stripoz = sp.ID_strpoz JOIN VRSTA_OGLASA vo ON o.FK_vrsta_oglasa = vo.ID_vrsta WHERE o.FK_status =  '2' AND sp.FK_pozicija =  '2' AND sp.FK_stranica =  '2'");
            if ($upit102->num_rows > 0) {
                while ($slika = $upit102->fetch_array()) {
                    $upit103 = $spojiBazu->selectDB("SELECT visina, sirina FROM POZICIJA WHERE ID_pozicija='2'");
                    $dimenzije = $upit103->fetch_array();
                    echo '
            <a href="'.$slika[2].'">
            <img onclick=brojiKlikove('. $slika[0] .') src="multimedija/' . $slika[1] . '" width='.$dimenzije[1].' height='.$dimenzije[0].'  alt="slika.png">
            </a>';
                }
            } else {
                echo'<img src="multimedija/prazna.png" width="350" height="250"  alt="slika.png">';
            }
            
            $upit104 = $spojiBazu->selectDB("SELECT o.ID_oglasa, o.slika_naziv, o.web_adresa FROM OGLAS o JOIN STRANICA_POZICIJA sp ON o.FK_stripoz = sp.ID_strpoz JOIN VRSTA_OGLASA vo ON o.FK_vrsta_oglasa = vo.ID_vrsta WHERE o.FK_status =  '2' AND sp.FK_pozicija =  '5' AND sp.FK_stranica =  '2'");
            if ($upit104->num_rows > 0) {
                while ($slika = $upit104->fetch_array()) {
                    $upit105 = $spojiBazu->selectDB("SELECT visina, sirina FROM POZICIJA WHERE ID_pozicija='5'");
                    $dimenzije = $upit105->fetch_array();
                    echo '
            <a onclick=brojiKlikove('. $slika[0] .') href="'.$slika[2].'">
            <img class="desno2" src="multimedija/' . $slika[1] . '" width='.$dimenzije[1].' height='.$dimenzije[0].'  alt="slika.png">
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

