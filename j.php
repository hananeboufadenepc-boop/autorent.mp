<?php
session_start();
include "config.php";

/* 🔒 SECURITE ADMIN */
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit();
}

/* ================= ACTIONS ================= */

/* ✔ ACCEPTER */
if (isset($_GET['ok'])) {
    $id = intval($_GET['ok']);
    $conn->query("UPDATE voiture SET statut='approved', disponible=1 WHERE idVoiture=$id");
    header("Location: admin_panel.php");
    exit();
}

/* ❌ REFUSER */
if (isset($_GET['no'])) {
    $id = intval($_GET['no']);
    $conn->query("UPDATE voiture SET statut='refused' WHERE idVoiture=$id");
    header("Location: admin_panel.php");
    exit();
}

/* 🗑 SUPPRIMER */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM voiture WHERE idVoiture=$id");
    header("Location: admin_panel.php");
    exit();
}

/* ================= DATA ================= */

$cars = $conn->query("SELECT * FROM voiture ORDER BY idVoiture DESC");
$pending = $conn->query("SELECT * FROM voiture WHERE statut='pending'");
$payments = $conn->query("
    SELECT p.*, r.idVoiture 
    FROM paiement p
    LEFT JOIN reservation r ON p.idReservation = r.idReservation
    ORDER BY p.idPaiement DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0b0b0f;
    color:white;
}

.container{
    width:95%;
    margin:20px auto;
}

h1{color:gold;text-align:center;}

.nav{
    display:flex;
    gap:10px;
    justify-content:center;
    margin-bottom:20px;
}

.nav a{
    padding:10px 15px;
    background:gold;
    color:black;
    text-decoration:none;
    border-radius:8px;
}

/* CARD */
.card{
    background:#1e1e1e;
    padding:15px;
    margin-bottom:10px;
    border-radius:10px;
}

img{
    width:120px;
    border-radius:10px;
}

/* BUTTONS */
.btn{
    padding:8px 10px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    margin-right:5px;
    text-decoration:none;
    display:inline-block;
}

.approve{background:green;color:white;}
.reject{background:red;color:white;}
.edit{background:orange;color:white;}
.delete{background:black;color:white;}
</style>

</head>

<body>

<div class="container">

<h1>👑 Admin Dashboard</h1>

<div class="nav">
    <a href="admin_panel.php">Voitures</a>
    <a href="admin_add_car.php">➕ Ajouter</a>
    <a href="admin_profile.php">👤 Profil</a>
    <a href="logout.php" style="background:red;color:white;">🚪 Déconnexion</a>
</div>

<!-- PENDING -->
<h2>⏳ Voitures en attente</h2>

<?php while($c = $pending->fetch_assoc()){ ?>
<div class="card">

    <img src="images/<?php echo $c['image']; ?>">

    <h3><?php echo $c['marque']." ".$c['modele']; ?></h3>

    <a class="btn approve" href="admin_panel.php?ok=<?php echo $c['idVoiture']; ?>">✔ Accepter</a>
    <a class="btn reject" href="admin_panel.php?no=<?php echo $c['idVoiture']; ?>">❌ Refuser</a>

</div>
<?php } ?>

<hr>

<!-- ALL CARS -->
<h2>🚗 Toutes les voitures</h2>

<?php while($c = $cars->fetch_assoc()){ ?>
<div class="card">

    <img src="images/<?php echo $c['image']; ?>">

    <h3><?php echo $c['marque']." ".$c['modele']; ?></h3>

    <p>💰 <?php echo $c['prixParJour']; ?> DA</p>

    <a class="btn edit" href="admin_edit_car.php?id=<?php echo $c['idVoiture']; ?>">Modifier</a>

    <a class="btn delete"
       href="admin_panel.php?delete=<?php echo $c['idVoiture']; ?>"
       onclick="return confirm('Supprimer cette voiture ?')">
       Supprimer
    </a>

</div>
<?php } ?>

<hr>

<!-- PAYMENTS -->
<h2>💳 Paiements</h2>

<?php while($p = $payments->fetch_assoc()){ ?>
<div class="card">
    <p>Transaction: <?php echo $p['transactionID']; ?></p>
    <p>Montant: <?php echo $p['montant']; ?> DA</p>
    <p>Status: <?php echo $p['statut']; ?></p>
</div>
<?php } ?>

</div>

</body>
</html>