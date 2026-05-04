<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

$user = $_SESSION['user'];
$idVoiture = intval($_GET['id'] ?? 0);

if ($idVoiture <= 0) {
    header("Location: dashboard.php");
    exit();
}

/* client */
$client = $conn->query("SELECT * FROM client WHERE nom='$user'")->fetch_assoc();
$idClient = $client['idClient'];

/* check si déjà en favoris */
$check = $conn->query("
    SELECT * FROM favoris 
    WHERE idClient=$idClient 
    AND idVoiture=$idVoiture
");

/* insert si pas encore */
if ($check->num_rows == 0) {
    $conn->query("
        INSERT INTO favoris (idClient, idVoiture)
        VALUES ($idClient, $idVoiture)
    ");
}

/* retour */
header("Location: favoris.php");
exit();
?>