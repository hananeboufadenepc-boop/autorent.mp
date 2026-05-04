<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

$user = $_SESSION['user'];
$msg = "";
$errors = [];

if (isset($_POST['add'])) {

    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];
    $couleur = $_POST['couleur'];
    $prix = $_POST['prix'];
    $tel = $_POST['telephone'];
    if (!preg_match('/^(07|06|05)[0-9]{8}$/', $tel)) {
    $errors[] = "❌ Numéro invalide (doit commencer par 07, 06 ou 05)";
}
if (empty($marque) || empty($modele) || empty($annee) || empty($prix)) {
    $errors[] = "❌ Veuillez remplir tous les champs obligatoires";
}

if (!is_numeric($prix) || $prix <= 0) {
    $errors[] = "❌ Prix invalide";
}

if (!is_numeric($annee) || $annee < 1990 || $annee > date("Y")) {
    $errors[] = "❌ Année invalide";
}
    $localisation = $_POST['localisation'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];

    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "images/".$image);

    $owner = $conn->query("SELECT * FROM client WHERE nom='$user'")->fetch_assoc();
    $idOwner = $owner['idClient'];

   if (empty($errors)) {

    $owner = $conn->query("SELECT * FROM client WHERE nom='$user'")->fetch_assoc();
    $idOwner = $owner['idClient'];

    $conn->query("
        INSERT INTO voiture
        (marque,modele,annee,couleur,prixParJour,localisation,description,image,telephoneProprietaire,idProprietaire,statut,disponible,categorie)
        VALUES
        ('$marque','$modele','$annee','$couleur','$prix','$localisation','$description','$image','$tel','$idOwner','pending',0,'$categorie')
    ");

    $msg = "✔ Voiture envoyée pour validation";

} else {
    $msg = implode("<br>", $errors);
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Owner Luxury Space</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0b0b0f;
    color:white;
    overflow-x:hidden;
}

/* GLOW BACKGROUND */
body::before{
    content:"";
    position:fixed;
    width:700px;
    height:700px;
    background:gold;
    filter:blur(220px);
    opacity:0.10;
    top:-150px;
    left:-150px;
}

/* CONTAINER */
.container{
    width:500px;
    margin:60px auto;
    padding:25px;

    background:rgba(255,255,255,0.05);
    backdrop-filter:blur(18px);

    border:1px solid rgba(255,215,0,0.25);
    border-radius:18px;
}

/* TITLE */
.title{
    text-align:center;
    font-size:24px;
    color:gold;
}

/* MESSAGE */
.msg{
    text-align:center;
    color:lightgreen;
}

/* INPUT + SELECT */
input, textarea, select{
    width:95%;
    padding:12px;
    margin-top:8px;

    border:none;
    border-radius:10px;

    background:rgba(255,255,255,0.08);
    color:white;
    transition:0.3s;
}

/* SELECT DARK */
select{
    background:#222;
    color:white;
}

/* OPTION TEXT */
option{
    color:black;
}

/* BUTTON */
.send{
    width:100%;
    margin-top:15px;
    padding:12px;

    border:none;
    border-radius:10px;

    background:gold;
    color:black;
    font-weight:bold;
}

/* BACK */
.back{
    display:block;
    margin-top:12px;
    text-align:center;

    padding:10px;
    border-radius:10px;

    border:1px solid gold;
    color:gold;

    text-decoration:none;
}

/* ===== LIGHT MODE ===== */
body.light{
    background:#f5f5f5;
    color:#111;
}

body.light .container{
    background:#ffffff;
    color:#111;
    border-color:#ddd;
}

body.light input,
body.light textarea,
body.light select{
    background:#eee;
    color:#111;
}

body.light option{
    color:black;
}
</style>

</head>

<body>

<div class="container">

<div class="title">🏢 Espace Propriétaire</div>

<p class="msg"><?php echo $msg; ?></p>

<form method="POST" enctype="multipart/form-data">

<input name="marque" placeholder="Marque" required>
<input name="modele" placeholder="Modèle" required>
<input name="annee" placeholder="Année" required>
<input name="couleur" placeholder="Couleur" required>
<input name="prix" placeholder="Prix par jour" required>

<input name="telephone" placeholder="Téléphone" required>
<input name="localisation" placeholder="Localisation" required>

<!-- ✅ CATEGORIE -->
<select name="categorie" required>
    <option value="">Catégorie</option>
    <option value="SUV">SUV</option>
    <option value="Sport">Sport</option>
    <option value="Berline">Berline</option>
    <option value="Luxe">Luxe</option>
    <option value="Citadine">Citadine</option>
</select>

<textarea name="description" placeholder="Description"></textarea>

<input type="file" name="image" required>

<button class="send" name="add">Envoyer pour validation</button>

</form>

<a href="dashboard.php" class="back">⬅ Retour Dashboard</a>

</div>

<script src="theme.js"></script>

</body>
</html>