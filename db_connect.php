<?php
define("USER","root");
define("PASS","root");
$password="";
try {
  $pdo = new PDO('mysql:host=localhost;dbname=dbphp', USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
?>

