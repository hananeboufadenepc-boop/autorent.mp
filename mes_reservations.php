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

/* ANNULATION */
if (isset($_GET['cancel'])) {

    $idRes = intval($_GET['cancel']);

    // 1. récupérer voiture liée
    $res = $conn->query("
        SELECT idVoiture 
        FROM reservation 
        WHERE idReservation=$idRes
    ")->fetch_assoc();

    $idVoiture = $res['idVoiture'];

    // 2. supprimer réservation
    $conn->query("
        DELETE FROM reservation 
        WHERE idReservation=$idRes AND idClient=$idClient
    ");

    // 3. remettre voiture disponible
    $conn->query("
        UPDATE voiture 
        SET disponible = 1 
        WHERE idVoiture = $idVoiture
    ");

    header("Location: mes_reservations.php?msg=cancel_ok");
    exit();
}

/* DATA */
$reservations = $conn->query("
    SELECT r.*, v.marque, v.modele, v.image, v.prixParJour
    FROM reservation r
    JOIN voiture v ON r.idVoiture = v.idVoiture
    WHERE r.idClient = $idClient
    ORDER BY r.idReservation DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Mes Réservations</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0b0b0f;
    color:white;
}

.container{
    width:90%;
    margin:40px auto;
}

.title{
    text-align:center;
    color:gold;
}

/* CARD */
.card{
    display:flex;
    gap:15px;
    background:#1e1e1e;
    padding:15px;
    margin-bottom:15px;
    border-radius:12px;
}

.card img{
    width:150px;
    height:100px;
    object-fit:cover;
    border-radius:10px;
}

/* CANCEL BUTTON */
.cancel-btn{
    margin-top:10px;
    padding:10px 14px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    background:linear-gradient(135deg,#ff4d4d,#cc0000);
    color:white;
    transition:0.3s;
}

.cancel-btn:hover{
    transform:scale(1.05);
}

/* SUCCESS */
.success{
    text-align:center;
    background:green;
    padding:10px;
    border-radius:10px;
    margin-bottom:10px;
}

/* ================= BACK BUTTON (JAUNE) ================= */
.back{
    display:inline-block;
    margin-top:30px;
    padding:12px 20px;
    border-radius:50px;
    background:linear-gradient(135deg,#d4af37,#f7e27a);
    color:#111;
    text-decoration:none;
    font-weight:bold;
    transition:0.3s;
}

.back:hover{
    transform:scale(1.05);
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.7);
    justify-content:center;
    align-items:center;
}

.modal-box{
    background:#111;
    padding:25px;
    border-radius:15px;
    text-align:center;
    width:320px;
    border:1px solid gold;
}

.modal-box h2{
    color:gold;
}

/* BUTTONS MODAL */
.yes{
    background:green;
    color:white;
    padding:10px 15px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    margin-right:10px;
}

.no{
    background:red;
    color:white;
    padding:10px 15px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

/* LIGHT MODE */
body.light{
    background:#f5f5f5;
    color:#111;
}

body.light .card{
    background:#fff;
    color:#111;
}

body.light .title{
    color:#d4af37;
}

body.light .modal-box{
    background:#fff;
    color:#111;
}

</style>
</head>

<body>

<div class="container">

<h1 class="title">📅 Mes Réservations</h1>

<?php if (isset($_GET['msg'])) { ?>
<div class="success">✔ Réservation annulée avec succès</div>
<?php } ?>

<?php while($r = $reservations->fetch_assoc()) { ?>

<div class="card">

    <img src="images/<?php echo $r['image']; ?>">

    <div>

        <h3 style="color:gold;">
            <?php echo $r['marque']." ".$r['modele']; ?>
        </h3>

        <p>📅 <?php echo $r['dateDebut']; ?> → <?php echo $r['dateFin']; ?></p>

        <p>💰 <?php echo $r['prixParJour']; ?> DA</p>

        <button class="cancel-btn"
            onclick="openModal(<?php echo $r['idReservation']; ?>)">
            ❌ Annuler
        </button>

    </div>

</div>

<?php } ?>

<!-- 🔥 BOUTON RETOUR JAUNE -->
<div style="text-align:center;">
    <a class="back" href="dashboard.php">⬅ Retour</a>
</div>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
    <div class="modal-box">

        <h2>⚠ Confirmation</h2>
        <p>Voulez-vous vraiment annuler cette réservation ?</p>

        <button class="yes" id="yesBtn">Oui</button>
        <button class="no" onclick="closeModal()">Non</button>

    </div>
</div>

<script>
let selectedId = null;

function openModal(id){
    selectedId = id;
    document.getElementById("modal").style.display = "flex";
}

function closeModal(){
    document.getElementById("modal").style.display = "none";
}

document.getElementById("yesBtn").onclick = function(){
    window.location.href = "mes_reservations.php?cancel=" + selectedId;
};

/* LIGHT MODE */
window.onload = function(){
    if(localStorage.getItem("mode") === "light"){
        document.body.classList.add("light");
    }
}
</script>

</body>
</html>