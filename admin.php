<?php
session_start();
include "config.php";

/* SECURITE SIMPLE (optionnel mais recommandé) */
if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

/* ACCEPT */
if (isset($_GET['ok'])) {

    $id = intval($_GET['ok']);

    $conn->query("
        UPDATE voiture 
        SET statut='approved', disponible=1 
        WHERE idVoiture=$id
    ");

    header("Location: admin.php");
    exit();
}

/* REFUSE */
if (isset($_GET['no'])) {

    $id = intval($_GET['no']);

    $conn->query("
        UPDATE voiture 
        SET statut='refused' 
        WHERE idVoiture=$id
    ");

    header("Location: admin.php");
    exit();
}

/* DATA */
$res = $conn->query("SELECT * FROM voiture WHERE statut='pending'");
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
    width:80%;
    margin:40px auto;
}

.card{
    background:#1a1a1a;
    padding:15px;
    margin:10px 0;
    border-radius:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.btn{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
}

.ok{
    background:green;
    color:white;
}

.no{
    background:red;
    color:white;
}

h2{
    text-align:center;
    color:gold;
}
</style>

</head>

<body>

<div class="container">

<h2>🛠 Admin Panel - Pending Cars</h2>

<?php while($c = $res->fetch_assoc()){ ?>

<div class="card">

    <div>
        <h3 style="color:gold;"><?php echo $c['marque']; ?> <?php echo $c['modele']; ?></h3>
        <p>💰 <?php echo $c['prixParJour']; ?> DA</p>
    </div>

    <div>
        <a href="?ok=<?php echo $c['idVoiture']; ?>">
            <button class="btn ok">✔ Accept</button>
        </a>

        <a href="?no=<?php echo $c['idVoiture']; ?>">
            <button class="btn no">❌ Reject</button>
        </a>
    </div>

</div>

<?php } ?>

</div>

</body>
</html>