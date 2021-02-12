<?php
require 'baza.class.php';
$spojiBazu = new Baza();
$spojiBazu->spojiDB();
if(!empty($_POST['korime'])) {
  $query = "SELECT * FROM KORISNIK WHERE korisnicko_ime='" . $_POST['korime'] . "'";
  $baza = $spojiBazu->selectDB($query);
  $dostupno = $baza->num_rows;
  if($dostupno>0) {
      echo "Korisničko ime nedostupno.";
  }else{
      echo "Korisničko ime dostupno.";
  }
}
$spojiBazu->zatvoriDB();


