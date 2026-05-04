<?php
include "config.php";

/* RECHERCHE */
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM voiture WHERE 1=1";

if ($search != '') {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (marque LIKE '%$search%' 
                OR modele LIKE '%$search%' 
                OR couleur LIKE '%$search%')";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Voitures</title>

<style>
body {
    margin:0;
    font-family:Arial;
    background:#121212;
    color:white;
}

/* BARRE RECHERCHE */
.search-bar {
    display:flex;
    justify-content:center;
    padding:20px;
    background:#1e1e1e;
}

.search-bar input {
    width:400px;
    padding:12px;
    border:none;
    border-radius:10px 0 0 10px;
    outline:none;
}

.search-bar button {
    padding:12px 20px;
    border:none;
    background:red;
    color:white;
    border-radius:0 10px 10px 0;
    cursor:pointer;
}

/* CARS */
.grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:15px;
    padding:20px;
}

.card {
    background:#1e1e1e;
    padding:15px;
    border-radius:12px;
}

.card img {
    width:100%;
    height:150px;
    object-fit:cover;
    border-radius:10px;
}

.price {
    color:gold;
}
</style>

</head>
<body>

<!-- 🔴 BARRE DE RECHERCHE -->
<form method="GET" class="search-bar">

<input type="text" name="search" placeholder="Rechercher marque, modèle, couleur...">

<button type="submit">🔍</button>

</form>

<!-- 🚗 VOITURES -->
<div class="grid">

<?php while($car = $result->fetch_assoc()) { ?>

<div class="card">

<img src="images/<?php echo $car['image']; ?>">

<h3><?php echo $car['marque']; ?> <?php echo $car['modele']; ?></h3>

<p class="price"><?php echo $car['prixParJour']; ?> DA / jour</p>

<p><?php echo $car['couleur'] ?? 'N/A'; ?></p>

<a href="car_details.php?id=<?php echo $car['idVoiture']; ?>">
Voir détails
</a>

</div>

<?php } ?>

</div>

</body>
</html>