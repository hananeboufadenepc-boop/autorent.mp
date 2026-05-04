<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

$user = $_SESSION['user'];

$client = $conn->query("SELECT * FROM client WHERE nom='$user'")->fetch_assoc();
$idClient = $client['idClient'];

$favoris = $conn->query("
    SELECT f.*, v.*
    FROM favoris f
    JOIN voiture v ON f.idVoiture = v.idVoiture
    WHERE f.idClient=$idClient
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Favoris</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0b0b0f;
    color:white;
}

body.light{
    background:#f5f5f5;
    color:#111;
}

.container{
    width:90%;
    margin:40px auto;
}

.card{
    display:flex;
    gap:15px;
    background:#1e1e1e;
    padding:15px;
    margin-bottom:15px;
    border-radius:12px;
}

body.light .card{
    background:#fff;
    color:#111;
}

.card img{
    width:150px;
    height:100px;
    object-fit:cover;
    border-radius:10px;
}

.btn{
    background:gold;
    border:none;
    padding:10px;
    border-radius:8px;
}
</style>

</head>

<body>

<div class="container">

<h1 style="text-align:center;color:gold;">❤️ Favoris</h1>

<?php while($f = $favoris->fetch_assoc()) { ?>

<div class="card">

<img src="images/<?php echo $f['image']; ?>">

<div>
<h3 style="color:gold;"><?php echo $f['marque']." ".$f['modele']; ?></h3>
<p>💰 <?php echo $f['prixParJour']; ?> DA</p>

<a href="car_details.php?id=<?php echo $f['idVoiture']; ?>">
    <button class="btn">Voir</button>
</a>

</div>

</div>

<?php } ?>

</div>

<script>
window.onload = function(){
    if(localStorage.getItem("mode")==="light"){
        document.body.classList.add("light");
    }
}
</script>
<div style="text-align:center;margin-top:30px;">
    <button onclick="history.back()" style="
        background:linear-gradient(135deg,#d4af37,#f7e27a);
        color:#111;
        border:none;
        padding:12px 20px;
        border-radius:10px;
        cursor:pointer;
        font-weight:bold;
        transition:0.3s;
    "
    onmouseover="this.style.transform='scale(1.05)'"
    onmouseout="this.style.transform='scale(1)'">
        ⬅ Retour
    </button>
</div>
</body>
</html>