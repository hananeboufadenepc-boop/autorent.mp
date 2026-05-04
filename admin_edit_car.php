<?php
session_start();
include "config.php";

/* 🔒 sécurité admin */
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit();
}

$id = intval($_GET['id']);

/* 🔍 récupérer voiture */
$car = $conn->query("SELECT * FROM voiture WHERE idVoiture=$id")->fetch_assoc();

$msg = "";

/* 💾 UPDATE */
if (isset($_POST['update'])) {

    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];
    $prix = $_POST['prix'];
    $couleur = $_POST['couleur'];
    $localisation = $_POST['localisation'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];

    /* 📸 IMAGE */
    $image = $car['image'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image);
    }

    $conn->query("
        UPDATE voiture SET
        marque='$marque',
        modele='$modele',
        annee='$annee',
        prixParJour='$prix',
        couleur='$couleur',
        localisation='$localisation',
        description='$description',
        categorie='$categorie',
        image='$image'
        WHERE idVoiture=$id
    ");

    $msg = "✔ Voiture modifiée avec succès";

    $car = $conn->query("SELECT * FROM voiture WHERE idVoiture=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Modifier Voiture</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0b0b0f;
    color:white;
}

.container{
    width:500px;
    margin:40px auto;
    padding:20px;
    background:#1e1e1e;
    border-radius:12px;
}

h2{
    text-align:center;
    color:gold;
}

input, textarea, select{
    width:100%;
    padding:10px;
    margin:8px 0;
    border:none;
    border-radius:8px;
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

img{
    width:100%;
    border-radius:10px;
    margin-bottom:10px;
}
</style>

</head>

<body>

<div class="container">

<h2>✏ Modifier Voiture</h2>

<p class="msg"><?php echo $msg; ?></p>

<!-- IMAGE ACTUELLE -->
<img src="images/<?php echo $car['image']; ?>">

<form method="POST" enctype="multipart/form-data">

<input name="marque" value="<?php echo $car['marque']; ?>" required>
<input name="modele" value="<?php echo $car['modele']; ?>" required>
<input name="annee" value="<?php echo $car['annee']; ?>" required>
<input name="prix" value="<?php echo $car['prixParJour']; ?>" required>
<input name="couleur" value="<?php echo $car['couleur']; ?>" required>
<input name="localisation" value="<?php echo $car['localisation']; ?>" required>

<!-- IMAGE -->
<input type="file" name="image">

<!-- CATEGORIE -->
<select name="categorie" required>
    <option value="">Catégorie</option>
    <option value="SUV" <?php if($car['categorie']=="SUV") echo "selected"; ?>>SUV</option>
    <option value="Sport" <?php if($car['categorie']=="Sport") echo "selected"; ?>>Sport</option>
    <option value="Berline" <?php if($car['categorie']=="Berline") echo "selected"; ?>>Berline</option>
    <option value="Luxe" <?php if($car['categorie']=="Luxe") echo "selected"; ?>>Luxe</option>
    <option value="Citadine" <?php if($car['categorie']=="Citadine") echo "selected"; ?>>Citadine</option>
</select>

<textarea name="description"><?php echo $car['description']; ?></textarea>

<button name="update">💾 Sauvegarder</button>

</form>

<br>

<a href="admin_panel.php" style="color:gold;">⬅ Retour</a>

</div>

</body>
</html>