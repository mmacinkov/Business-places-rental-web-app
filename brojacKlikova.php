<?php

require 'baza.class.php';
$spojiBazu = new Baza();
$spojiBazu->spojiDB();
$IDKlikovi = $_GET['id'];
$upit109 = $spojiBazu->selectDB("UPDATE OGLAS SET broj_klikova = broj_klikova + 1 WHERE ID_oglasa='$IDKlikovi'"); 
$spojiBazu->zatvoriDB();