<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

$id = intval($_GET['id']);
$car = $conn->query("SELECT * FROM voiture WHERE idVoiture=$id")->fetch_assoc();

$owner = $conn->query("
    SELECT * FROM client WHERE role='admin' LIMIT 1
")->fetch_assoc();

$user = $_SESSION['user'];

$msg = "";
$msgAvis = "";
$isFav = false;
$errors = [];
$userSafe = $conn->real_escape_string($user);

$client = $conn->query("
    SELECT * FROM client 
    WHERE nom='$userSafe'
")->fetch_assoc();
$idClient = $client['idClient'];

$checkFav = $conn->query("
    SELECT * FROM favoris 
    WHERE idClient=$idClient AND idVoiture=$id
");

if ($checkFav->num_rows > 0) {
    $isFav = true;
}

if (isset($_POST['reserve'])) {

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $cin = trim($_POST['cin']);
    $telephone = trim($_POST['telephone']);
    $lieu = trim($_POST['lieu']);
    $dateDebut = $_POST['date_debut'];
    $dateFin = $_POST['date_fin'];
    $numCarte = trim($_POST['carte']);

    $errors = [];
  
    /* VALIDATION */
    if (empty($nom)) {
        $errors['nom'] = "Nom obligatoire";
    }

    if (empty($prenom)) {
        $errors['prenom'] = "Prénom obligatoire";
    }

    if (empty($cin)) {
        $errors['cin'] = "CIN obligatoire";
    } elseif (!preg_match("/^[0-9]{6,12}$/", $cin)) {
        $errors['cin'] = "CIN invalide";
    }

    if (empty($telephone)) {
        $errors['telephone'] = "Téléphone obligatoire";
    } elseif (!preg_match("/^(05|06|07)[0-9]{8}$/", $telephone)) {
        $errors['telephone'] = "Numéro invalide";
    }

    if (empty($dateDebut) || empty($dateFin)) {
        $errors['date'] = "Dates obligatoires";
    } elseif (strtotime($dateFin) <= strtotime($dateDebut)) {
        $errors['date'] = "Date fin invalide";
    }

    if (empty($numCarte)) {
        $errors['carte'] = "Carte obligatoire";
    }

    if ($car['disponible'] == 0) {
        $errors['global'] = "Voiture déjà louée";
    }
 if (!empty($errors)) {
    $openModal = true; // 🔥 on garde le modal ouvert si erreur
 } else {
    $openModal = false;
 }
    /* EXECUTION */
   
   if (empty($errors)) {

    $conn->query("
        INSERT INTO reservation
        (dateDebut,dateFin,statut,idClient,idVoiture,nom,prenom,cin,telephone,lieu)
        VALUES
        ('$dateDebut','$dateFin','en attente','$idClient','$id','$nom','$prenom','$cin','$telephone','$lieu')
    ");

    $conn->query("UPDATE voiture SET disponible = 0 WHERE idVoiture = $id");

    $idReservation = $conn->insert_id;

    $days = (strtotime($dateFin) - strtotime($dateDebut)) / (60*60*24);
    $montant = $days * $car['prixParJour'];

    $transactionID = "TRX".time().rand(100,999);

    $conn->query("
        INSERT INTO paiement
        (montant, mode, transactionID, statut, idReservation)
        VALUES
        ('$montant','carte','$transactionID','payé','$idReservation')
    ");

    $_SESSION['msg'] = "✔ Réservation confirmée ";
 $_SESSION['delivery_msg'] = "🚚 Cette voiture sera livrée sous 24h à : " . $lieu;

    header("Location: ".$_SERVER['PHP_SELF']."?id=".$id);
    exit();}
}


   


/* ❤️ FAVORIS TOGGLE */
if (isset($_POST['favori'])) {

    if ($checkFav->num_rows == 0) {

        $conn->query("
            INSERT INTO favoris (idClient, idVoiture)
            VALUES ($idClient, $id)
        ");

        $_SESSION['msg'] = "❤️ Ajouté aux favoris";

    } else {

        $conn->query("
            DELETE FROM favoris 
            WHERE idClient=$idClient AND idVoiture=$id
        ");

        $_SESSION['msg'] = "💔 Retiré des favoris";
    }

    // 🔥 IMPORTANT (évite bug formulaire)
    header("Location: ".$_SERVER['PHP_SELF']."?id=".$id);
    exit();
}

/* AVIS */
if (isset($_POST['avis'])) {

    $note = intval($_POST['note']);
    $commentaire = $_POST['commentaire'];

    $client = $conn->query("SELECT * FROM client WHERE nom='$user'")->fetch_assoc();
    $idClient = $client['idClient'];

    $ok = $conn->query("
        INSERT INTO avis (note, commentaire, date, idClient, idVoiture)
        VALUES ('$note', '$commentaire', CURDATE(), '$idClient', '$id')
    ");

    if ($ok) {
        $msgAvis = "✔ Avis ajouté avec succès";
        header("Location: ".$_SERVER['PHP_SELF']."?id=".$id);
exit();
    } else {
        $msgAvis = "❌ Erreur lors de l'ajout";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Car Details</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0b0b0f;
    color:white;
}

/* 🌞 LIGHT MODE */
body.light{
    background:#f5f5f5;
    color:#111;
}

.container{
    max-width:900px;
    margin:40px auto;
    padding:20px;
}

img{
    width:100%;
    height:400px;
    object-fit:cover;
    border-radius:15px;
}

h1{
    color:gold;
    text-align:center;
}

body.light h1{
    color:#d4af37;
}

.grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
}

.box{
    background:#111;
    padding:10px;
    border-radius:10px;
}

body.light .box{
    background:#fff;
    color:#111;
    border:1px solid #ddd;
}

.btns{
    display:flex;
    gap:12px;
    margin-top:15px;
}

button{
    flex:1;
    padding:16px;
    border:none;
    border-radius:12px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
}
button{
    flex:1;
    height:45px;
    display:flex;
    align-items:center;
    justify-content:center;
    
}
.back{background:#222;color:white;}
.contact{background:#444;color:white;}
.rent{background:linear-gradient(135deg,#d4af37,#f7e27a);}
.fav-btn{background:linear-gradient(135deg,#ff4d6d,#ff7aa2);color:white;}

body.light .back{background:#ddd;color:#111;}
body.light .contact{background:#bbb;color:#111;}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,0.85);
}

.modal-content{
    width:420px;
    margin:5% auto;
    background:#111;
    padding:20px;
    border-radius:15px;
    position:relative;
}

body.light .modal-content{
    background:#fff;
    color:#111;
}

.close{
    position:absolute;
    top:10px;
    right:15px;
    cursor:pointer;
    font-size:20px;
}

/* FORM */
input,textarea,select{
    width:95%;
    padding:10px;
    margin:6px 0;
    border-radius:8px;
    border:none;
    font-size:14px;
}

body.light input,
body.light textarea,
body.light select{
    border:1px solid #ccc;
}

/* textarea = button */
form textarea,
form button{
    width:95%;
    height:45px;
    box-sizing:border-box;
}

form textarea{
    resize:none;
}

/* TOTAL */
#total{
    text-align:center;
    color:gold;
    font-weight:bold;
    margin-top:10px;
}

body.light #total{
    color:#d4af37;
}

/* AVIS */
.avis{
    background:#111;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
}

body.light .avis{
    background:#fff;
    color:#111;
    border:1px solid #ddd;
}

.stars{
    color:gold;
}
/* 🔥 bouton confirmer dans formulaire */
.modal-content form button{
    width:100%;
    height:45px;   /* 🔽 plus petit */
    font-size:13px;
    padding:8px;
    box-sizing:border-box;
}
/* 📞 CONTACT MODAL */
.contact-modal{
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,0.85);
}

.contact-box{
    width:350px;
    margin:10% auto;
    background:#111;
    padding:20px;
    border-radius:15px;
    text-align:center;
    border:1px solid gold;
}

body.light .contact-box{
    background:#fff;
    color:#111;
}

.contact-box h2{
    color:gold;
    margin-bottom:10px;
}

.contact-info{
    margin:10px 0;
    font-size:16px;
    line-height:1.8;
}

.contact-close{
    cursor:pointer;
    color:red;
    font-size:18px;
    float:right;
}
</style>
</head>

<body>

<div class="container">

<img src="images/<?php echo $car['image']; ?>">

<h1><?php echo $car['marque']." ".$car['modele']; ?></h1>

<p style="text-align:center;color:lightgreen;">
    <?php 
if(isset($_SESSION['msg'])){
    echo "<div style='color:lightgreen;text-align:center;margin-bottom:5px;'>".$_SESSION['msg']."</div>";
    unset($_SESSION['msg']);
}

if(isset($_SESSION['delivery_msg'])){
    echo "<div style='color:gold;text-align:center;font-size:14px;'>".$_SESSION['delivery_msg']."</div>";
    unset($_SESSION['delivery_msg']);
}
?>
</p>

<div class="grid">
<div class="box">🚗 <?php echo $car['marque']; ?></div>
<div class="box">📅 <?php echo $car['annee']; ?></div>
<div class="box">🎨 <?php echo $car['couleur']; ?></div>
<div class="box">💰 <?php echo $car['prixParJour']; ?> DA</div>
<div class="box">👥 <?php echo $car['nbPlaces']; ?></div>
<div class="box">⚡ <?php echo $car['disponible'] ? "Disponible" : "Indisponible"; ?></div>
</div>

<div class="btns">
<a href="dashboard.php">
    <button class="back">⬅ Retour</button>
</a>
<button class="contact" onclick="showContact()">📞 Contact</button>
<form method="POST" style="display:inline;">
    <button class="fav-btn" name="favori">
        <?php echo $isFav ? "💔 Retirer" : "❤️ Favoris"; ?>
    </button>
</form>
<button class="rent" onclick="openModal()">🚗 Louer</button>
</div>

<!-- MODAL -->
<div class="modal" id="modal" style="<?= (!empty($errors)) ? 'display:block;' : '' ?>">
<div class="modal-content">

<span class="close" onclick="closeModal()">✖</span>

<h2 style="text-align:center;color:gold;">Réservation</h2>
<?php if (!empty($errors['global'])): ?>
    <div style="background:red;color:white;padding:10px;border-radius:8px;margin-bottom:10px;">
        <?= $errors['global'] ?>
    </div>
<?php endif; ?>
<form method="POST">

<input name="nom" placeholder="Nom" value="<?php echo $_POST['nom'] ?? ''; ?>">
<?php if(isset($errors['nom'])) echo "<small style='color:red'>{$errors['nom']}</small>"; ?>
<input name="prenom" placeholder="Prénom">
<?php if(isset($errors['prenom'])) echo "<small style='color:red'>{$errors['prenom']}</small>"; ?>
<input name="cin" placeholder="CIN">
<?php if(isset($errors['cin'])) echo "<small style='color:red'>{$errors['cin']}</small>"; ?>
<input name="telephone" placeholder="Téléphone">
<input name="lieu" placeholder="Lieu" id="lieu">
<div id="deliveryMsg" style="color:lightgreen;margin:5px 0;font-size:13px;"></div>
<?php if(isset($errors['lieu'])) echo "<small style='color:red'>{$errors['lieu']}</small>"; ?>
<input type="date" name="date_debut">
<input type="date" name="date_fin">
<?php if(isset($errors['date'])) echo "<small style='color:red'>{$errors['date']}</small>"; ?>


<div id="total">Total: 0 DA</div>

<input name="carte" placeholder="Carte bancaire">
<?php if(isset($errors['carte'])) echo "<small style='color:red'>{$errors['carte']}</small>"; ?>
<button class="rent" name="reserve">Confirmer</button>

</form>

</div>
</div>

<!-- AVIS -->
<h2 style="text-align:center;color:gold;">⭐ Avis clients</h2>

<p style="text-align:center;color:lightgreen;">
    <?php echo $msgAvis; ?>
</p>

<form method="POST" style="width:60%;margin:auto;display:flex;flex-direction:column;gap:10px;">

<select name="note">
<option value="1">⭐</option>
<option value="2">⭐⭐</option>
<option value="3">⭐⭐⭐</option>
<option value="4">⭐⭐⭐⭐</option>
<option value="5">⭐⭐⭐⭐⭐</option>
</select>

<textarea name="commentaire" placeholder="Écrire un avis..."></textarea>

<button class="rent" name="avis">Envoyer</button>

</form>

<?php
$avis = $conn->query("
SELECT avis.*, client.nom 
FROM avis 
JOIN client ON avis.idClient = client.idClient
WHERE idVoiture=$id ORDER BY idAvis DESC");

while($a = $avis->fetch_assoc()){
?>
<div class="avis">
<div class="stars">
<?php for($i=0;$i<$a['note'];$i++) echo "⭐"; ?>
</div>
<p><?php echo $a['commentaire']; ?></p>
<small><?php echo $a['nom']." - ".$a['date']; ?></small>
</div>
<?php } ?>

</div>

<script>
function openModal(){
    document.getElementById("modal").style.display="block";
}
function closeModal(){
    document.getElementById("modal").style.display="none";
}

/* TOTAL */
const prix = <?php echo $car['prixParJour']; ?>;

document.querySelector("input[name='date_debut']").addEventListener("change", calc);
document.querySelector("input[name='date_fin']").addEventListener("change", calc);

function calc(){
    let start = new Date(document.querySelector("input[name='date_debut']").value);
    let end = new Date(document.querySelector("input[name='date_fin']").value);

    if(start && end && end > start){
        let diff = (end - start) / (1000*60*60*24);
        document.getElementById("total").innerText = "Total: " + (diff * prix) + " DA";
    }
}
function showContact(){
    document.getElementById("contactModal").style.display="block";
}

function closeContact(){
    document.getElementById("contactModal").style.display="none";
}

/* 🌗 THEME */
if(localStorage.getItem("mode") === "light"){
    document.body.classList.add("light");
}
</script>
<!-- 📞 CONTACT MODAL -->
<div class="contact-modal" id="contactModal">

    <div class="contact-box">

        <span class="contact-close" onclick="closeContact()">✖</span>

        <h2>📞 Contact Admin</h2>

        <div class="contact-info">
            📧 Email: <?php echo $owner['email']; ?> <br><br>
            📱 Téléphone: 0794194909
        </div>

        <button onclick="closeContact()" style="margin-top:10px;padding:10px;width:100%;background:gold;border:none;border-radius:8px;">
            OK
        </button>

    </div>

</div>
<script>
document.querySelector("input[name='nom']").addEventListener("input", e=>{
    e.target.value = e.target.value.replace(/[^a-zA-ZÀ-ÿ\s]/g,'');
});

document.querySelector("input[name='prenom']").addEventListener("input", e=>{
    e.target.value = e.target.value.replace(/[^a-zA-ZÀ-ÿ\s]/g,'');
});

document.querySelector("input[name='telephone']").addEventListener("input", e=>{
    e.target.value = e.target.value.replace(/[^0-9]/g,'');
});

document.querySelector("input[name='cin']").addEventListener("input", e=>{
    e.target.value = e.target.value.replace(/[^0-9]/g,'');
});
</script>
<script>
let openModalOnError = <?= !empty($errors) ? 'true' : 'false' ?>;

if (openModalOnError) {
    document.getElementById("modal").style.display = "block";
}
</script>
<script>
let hasErrors = <?= !empty($errors) ? 'true' : 'false' ?>;

if (hasErrors) {
    document.getElementById("modal").style.display = "block";
}
</script>
<script>
document.getElementById("lieu").addEventListener("input", function () {
    let msg = document.getElementById("deliveryMsg");

    if (this.value.trim().length > 0) {
        msg.innerText = "Une adresse exacte garantit une bonne livraison";
    } else {
        msg.innerText = "";
    }
});
</script>
</body>
</html>