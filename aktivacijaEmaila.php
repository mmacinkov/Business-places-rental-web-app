<?php
require 'baza.class.php';
$spojiBazu = new Baza();
$spojiBazu->spojiDB();
    $hash = $_GET['kriptiranaL'];   
    $pretrazi = $spojiBazu->selectDB("SELECT korisnicko_ime, aktivni FROM KORISNIK WHERE kriptirana_lozinka='$hash' AND aktivni='0'"); 
    $korime = $pretrazi->fetch_array();
    $vrijed = $pretrazi->num_rows;  
    
    $trenutnoVrijeme = date('Y-m-d H:i:s');
    if($vrijed > 0){
        $pretraziVrijeme = $spojiBazu->selectDB("SELECT vrijemeRegistracije FROM KORISNIK WHERE kriptirana_lozinka = '$hash' AND vrijemeRegistracije > DATE_SUB('$trenutnoVrijeme', INTERVAL 7 HOUR)");
        $vrijed2 = $pretraziVrijeme->num_rows;
        if ($vrijed2 > 0){
        $spojiBazu->updateDB("UPDATE KORISNIK SET aktivni = '1' WHERE kriptirana_lozinka='$hash'");
        $spojiBazu->selectDB("INSERT INTO DNEVNIK_RADA VALUES (default, '$korime[0]', 'Aktivacija racuna', now(), 'Aktiviranje')");
        echo 'Vaš račun je uspješno aktiviran! Molimo pričekajte par sekundi i bit ćete preusmjereni na prijavu.';
        header("Refresh: 5; url = prijava.php");
        }
        else{
            echo 'Vaš aktivacijski kod je istekao! Molimo ponovno se registrirajte.';
            header("Refresh: 5; url = registracija.php");
        }
    }else{
        echo 'Vaš račun je već aktiviran! Molimo pričekajte par sekundi i bit ćete preusmjereni na prijavu.';
        header("Refresh: 5; url = prijava.php");
    }          
$spojiBazu->zatvoriDB();
?>
<meta charset="utf-8">