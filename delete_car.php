<?php
include "config.php";

$id = $_GET['id'];

$conn->query("DELETE FROM voiture WHERE idVoiture=$id");

header("Location: owner_cars.php");
exit();
?>