<?php
session_start();
include "config.php";

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit();
}

$user = $_SESSION['user'];

/* GET ADMIN DATA */
$admin = $conn->query("
    SELECT * FROM client 
    WHERE nom='$user' AND role='admin'
")->fetch_assoc();

$msg = "";

/* UPDATE PROFILE */
if (isset($_POST['update'])) {

    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn->query("
        UPDATE client 
        SET nom='$nom',
            email='$email',
            motDePasse='$password'
        WHERE idClient=".$admin['idClient']."
    ");

    $_SESSION['user'] = $nom;
    $msg = "✔ Profil mis à jour";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Profile</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0b0b0f;
    color:white;
}

.container{
    width:420px;
    margin:60px auto;
    background:#1e1e1e;
    padding:25px;
    border-radius:15px;
    text-align:center;
}

h1{
    color:gold;
}

input{
    width:100%;
    padding:12px;
    margin:8px 0;
    border:none;
    border-radius:10px;
    background:#111;
    color:white;
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
    color:lightgreen;
    margin-bottom:10px;
}
</style>

</head>

<body>

<div class="container">

<h1>👑 Admin Profile</h1>

<p class="msg"><?php echo $msg; ?></p>

<form method="POST">

<input type="text" name="nom" value="<?php echo $admin['nom']; ?>" required>

<input type="email" name="email" value="<?php echo $admin['email']; ?>" required>

<input type="text" name="password" value="<?php echo $admin['motDePasse']; ?>" required>

<button name="update">💾 Modifier</button>

</form>

<br>
<a href="admin_panel.php" style="color:gold;">⬅ Retour Dashboard</a>

</div>

</body>
</html>