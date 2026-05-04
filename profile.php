<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

$user = $_SESSION['user'];

$client = $conn->query("SELECT * FROM client WHERE nom='$user'")->fetch_assoc();
$message = "";

/* AUTO UPLOAD IMAGE */
if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {

    $fileName = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowed = ['png', 'jpg', 'jpeg', 'webp'];

    if (!in_array($ext, $allowed)) {
        die("❌ Format non supporté (png, jpg, jpeg, webp uniquement)");
    }

    $imageName = time() . "_" . $fileName;

    move_uploaded_file($tmp, "images/" . $imageName);

    $conn->query("
        UPDATE client 
        SET image='$imageName'
        WHERE nom='$user'
    ");

    $client['image'] = $imageName;
}

/* UPDATE PROFILE */
if (isset($_POST['update'])) {

    $newNom = $_POST['nom'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['password'];

    $conn->query("
        UPDATE client 
        SET nom='$newNom', email='$newEmail', motDePasse='$newPassword'
        WHERE nom='$user'
    ");

    $_SESSION['user'] = $newNom;
    $message = "✔ Profil mis à jour";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Profil</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0b0b0f;
    color:white;
}

/* GLOW */
body::before{
    content:"";
    position:fixed;
    width:500px;
    height:500px;
    background:gold;
    filter:blur(180px);
    opacity:0.12;
}

/* CARD */
.profile-container{
    width:420px;
    margin:60px auto;
    padding:25px;
    background:rgba(255,255,255,0.05);
    border-radius:18px;
    border:1px solid rgba(255,215,0,0.25);
    text-align:center;
}

/* IMAGE */
.profile-img{
    width:110px;
    height:110px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid gold;
}

/* TITLE */
h1{
    color:gold;
}

/* INPUT */
input{
    width:95%;
    padding:12px;
    margin-top:8px;
    border:none;
    border-radius:10px;
    background:rgba(255,255,255,0.08);
    color:white;
}

/* BUTTON */
button{
    width:100%;
    margin-top:15px;
    padding:12px;
    border:none;
    border-radius:10px;
    background:gold;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.03);
}

/* PHOTO BUTTON */
.photo-btn{
    margin-top:10px;
    padding:10px;
    border:none;
    border-radius:10px;
    background:#333;
    color:white;
    cursor:pointer;
}

/* BACK */
a{
    display:block;
    margin-top:15px;
    color:gold;
    text-decoration:none;
}

/* LIGHT MODE */
body.light{
    background:#f5f5f5;
    color:#111;
}

body.light .profile-container{
    background:#fff;
    color:#111;
}

body.light input{
    background:#eee;
    color:#111;
}
</style>

</head>

<body>

<div class="profile-container">

    <!-- IMAGE -->
    <?php
    $img = !empty($client['image']) ? $client['image'] : 'default.png';
    ?>
    <img class="profile-img" src="images/<?php echo $img; ?>">

    <h1>👤 Mon Profil</h1>

    <p style="color:lightgreen;"><?php echo $message; ?></p>

    <!-- PHOTO UPLOAD -->
    <form id="photoForm" method="POST" enctype="multipart/form-data">

        <input type="file" name="image" id="imageInput" accept="image/*" hidden>

        <button type="button" class="photo-btn"
        onclick="document.getElementById('imageInput').click()">
            📷 Ajouter photo
        </button>

    </form>

    <!-- UPDATE FORM -->
    <form method="POST">

        <input type="text" name="nom" value="<?php echo $client['nom']; ?>">
        <input type="email" name="email" value="<?php echo $client['email']; ?>">
        <input type="text" name="password" value="<?php echo $client['motDePasse']; ?>">

        <button name="update">Sauvegarder</button>

    </form>

    <a href="dashboard.php">⬅ Retour Dashboard</a>

</div>

<script>
/* AUTO UPLOAD */
document.getElementById("imageInput").addEventListener("change", function () {
    document.getElementById("photoForm").submit();
});

/* THEME */
if(localStorage.getItem("mode") === "light"){
    document.body.classList.add("light");
}
</script>

</body>
</html>