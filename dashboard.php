<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

$user = $_SESSION['user'];

/* CLIENT */
$client = $conn->query("SELECT * FROM client WHERE nom='$user'")->fetch_assoc();

/* SEARCH + FILTERS */
$search = $_GET['search'] ?? '';
$prix = $_GET['prix'] ?? '';
$categorie = $_GET['categorie'] ?? '';

$sql = "SELECT * FROM voiture 
        WHERE statut='approved' 
        AND disponible=1";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (
        marque LIKE '%$search%' 
        OR modele LIKE '%$search%' 
        OR couleur LIKE '%$search%'
    )";
}

if (!empty($prix)) {
    $prix = intval($prix);
    $sql .= " AND prixParJour <= $prix";
}

if (!empty($categorie)) {
    $categorie = $conn->real_escape_string($categorie);
    $sql .= " AND categorie='$categorie'";
}

$voitures = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>AutoRent Dashboard</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    color: white;
    overflow-x: hidden;
    background-color: #0b0b0f;
}

/* LIGHT MODE */
body.light {
    background: #f5f5f5;
    color: #111;
}

body.light .sidebar { background:#fff; }
body.light .profile { background:#eee; }
body.light .menu a { color:#111; }
body.light .card { background:#fff; color:#111; }

.sidebar {
    width: 200px;
    background: #1c1c1c;
    height: 100vh;
    position: fixed;
    padding: 15px;
}

/* PROFILE */
.profile {
    background: #2a2a2a;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 15px;
    text-align:center;
}

/* PROFILE IMAGE */
.profile img{
    width:70px;
    height:70px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid gold;
    margin-bottom:8px;
}

/* 🔥 EMAIL REDUCED SIZE */
.email-small{
    font-size: 12px;
    color: #aaa;
    word-break: break-word;
    line-height: 1.3;
}

/* MENU */
.menu a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 8px;
    margin-top: 8px;
    border-radius: 8px;
}

.menu a:hover {
    background: gold;
    color: black;
}

.main {
    margin-left: 200px;
    padding: 20px;
}

.title {
    text-align: center;
    color: gold;
}

.search-bar {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 20px;
}

.search-bar input,
.search-bar select {
    padding: 12px;
    border-radius: 10px;
    border: none;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px,1fr));
    gap: 15px;
}

.card {
    background: #1e1e1e;
    padding: 15px;
    border-radius: 12px;
}

.card img {
    width: 100%;
    height: 170px;
    object-fit: cover;
    border-radius: 10px;
}

.btn {
    background: linear-gradient(135deg, #d4af37, #f7e27a);
    color: #111;
    padding: 12px;
    border: none;
    width: 100%;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    position: relative;
    overflow: hidden;
}
.btn::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.3);
    transform: skewX(-25deg);
    transition: 0.5s;
}

.btn:hover::after {
    left: 120%;
}
</style>
</head>

<body>

<div class="sidebar">

    <div class="profile">

        <?php
        $img = !empty($client['image']) ? $client['image'] : 'default.png';
        ?>

        <img src="images/<?php echo $img; ?>">

        <h3>👤 <?php echo $client['nom']; ?></h3>

        <!-- 🔥 EMAIL SMALL -->
        <p class="email-small"><?php echo $client['email']; ?></p>

        <a href="profile.php">
            <button style="width:100%;padding:8px;background:gold;border:none;border-radius:8px;">
                Profil
            </button>
        </a>

        <button onclick="toggleMode()"
            style="width:100%;margin-top:10px;padding:8px;background:#444;color:white;border:none;border-radius:8px;">
            🌙 / ☀️ Mode
        </button>

    </div>

    <div class="menu">
        <a href="dashboard.php">🚘 Voitures</a>
        <a href="mes_reservations.php">📅 Mes réservations</a>
        <a href="favoris.php">❤️ Favoris</a>
        <a href="owner_add_car.php">🏢 Espace Propriétaire</a>
        <a href="logout.php">🚪 Déconnexion</a>
    </div>

</div>

<div class="main">

<h1 class="title">Voitures disponibles</h1>

<form class="search-bar" method="GET">
    <input type="text" name="search" placeholder="Rechercher..." value="<?php echo $search; ?>">

    <select name="categorie">
        <option value="">Catégorie</option>
        <option value="SUV">SUV</option>
        <option value="Sport">Sport</option>
        <option value="Berline">Berline</option>
        <option value="Luxe">Luxe</option>
        <option value="Citadine">Citadine</option>
    </select>
    

    <input type="number" name="prix" placeholder="Prix max" value="<?php echo $prix; ?>">

    <button type="submit">🔍</button>
</form>

<div class="grid">

<?php while($car = $voitures->fetch_assoc()) { ?>

<div class="card">

<img src="images/<?php echo $car['image']; ?>">

<h3 style="color:gold;">
    <?php echo $car['marque']." ".$car['modele']; ?>
</h3>

<p>💰 <?php echo $car['prixParJour']; ?> DA</p>
<p>
    <?php 
    if ($car['disponible'] == 1) {
        echo "🟢 Disponible";
    } else {
        echo "🔴 Indisponible";
    }
    ?>
</p>
<a href="car_details.php?id=<?php echo $car['idVoiture']; ?>">
    <button class="btn">Voir détails</button>
</a>

</div>

<?php } ?>

</div>

</div>

<script>
function toggleMode(){
    document.body.classList.toggle("light");
    localStorage.setItem("mode",
        document.body.classList.contains("light") ? "light" : "dark"
    );
}

window.onload = function(){
    if(localStorage.getItem("mode") === "light"){
        document.body.classList.add("light");
    }
}
</script>

</body>
</html>