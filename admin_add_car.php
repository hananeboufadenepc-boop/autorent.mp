<?php
session_start();
include "config.php";

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit();
}

$msg = "";

if (isset($_POST['add'])) {

    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $categorie = $_POST['categorie'];
    $annee = $_POST['annee'];
    $prix = $_POST['prix'];
    $couleur = $_POST['couleur'];
    $localisation = $_POST['localisation'];
    $description = $_POST['description'];

    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "images/".$image);

    $conn->query("
        INSERT INTO voiture
        (marque, modele, categorie, annee, prixParJour, couleur, localisation, description, image, statut, disponible)
        VALUES
        ('$marque', '$modele', '$categorie', '$annee', '$prix', '$couleur', '$localisation', '$description', '$image', 'approved', 1)
    ");

    $msg = "✔ Voiture ajoutée avec succès";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ajouter Voiture</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0b0b0f;
    color:white;
}

.container{
    width:400px;
    margin:60px auto;
    background:#1e1e1e;
    padding:20px;
    border-radius:15px;
}

h1{color:gold;text-align:center;}

input,textarea{
    width:100%;
    padding:10px;
    margin:8px 0;
    border:none;
    border-radius:8px;
    background:#111;
    color:white;
}

button{
    width:100%;
    padding:12px;
    background:gold;
    border:none;
    border-radius:10px;
    font-weight:bold;
    cursor:pointer;
}

.msg{
    text-align:center;
    color:lightgreen;
}
</style>

</head>

<body>

<div class="container">

<h1>➕ Ajouter Voiture</h1>

<p class="msg"><?php echo $msg; ?></p>

<form method="POST" enctype="multipart/form-data">

<input name="marque" placeholder="Marque" required>
<input name="modele" placeholder="Modèle" required>

<input name="categorie" placeholder="Catégorie (SUV, Berline...)" required>

<input name="annee" placeholder="Année" required>
<input name="prix" placeholder="Prix par jour" required>
<input name="couleur" placeholder="Couleur" required>
<input name="localisation" placeholder="Localisation" required>

<textarea name="description" placeholder="Description"></textarea>

<input type="file" name="image" required>

<button name="add">Ajouter</button>

</form>

<br>
<a href="admin_panel.php" style="color:gold;">⬅ Retour</a>

</div>

</body>
</html>