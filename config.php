<?php

$host = "localhost";        // serveur local
$user = "root";             // utilisateur par défaut en local
$password = "";             // mot de passe vide par défaut (XAMPP/WAMP)
$database = "Car_rental";   // nom de ta base de données

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Erreur connexion MySQL: " . mysqli_connect_error());
}

echo "Connexion réussie à la base de données !";

?>