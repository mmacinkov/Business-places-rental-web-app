<?php

require 'baza.class.php';
$spojiBazu = new Baza;
$spojiBazu->spojiDB();
session_start();
$korisnik = $_SESSION['korisnik'];
if(isset($korisnik)){
$spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korisnik', 'Odjava korisnika', now(), 'Odjava korisnika sa stranice')");
$spojiBazu->zatvoriDB();
}
unset($_SESSION['korisnik']);
header("Location: neregistriraniKorisnik.php");