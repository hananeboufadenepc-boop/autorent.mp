<?php
session_start();
include "config.php";

$email = $_SESSION['reset_email'] ?? null;

if (!$email) {
    die("❌ Accès refusé");
}

$msg = "";

if (isset($_POST['reset'])) {

    $password = $_POST['password'];

    $conn->query("
        UPDATE client 
        SET motDePasse='$password'
        WHERE email='$email'
    ");

    unset($_SESSION['reset_email']);

    $msg = "✔ Mot de passe changé avec succès";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>

<style>
body{
    font-family:Arial;
    background:#0b0b0f;
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    width:350px;
    padding:20px;
    background:#111;
    border:1px solid gold;
    border-radius:12px;
    text-align:center;
}

input{
    width:90%;
    padding:10px;
    margin:10px 0;
}

button{
    padding:10px;
    width:95%;
    background:gold;
    border:none;
    cursor:pointer;
    font-weight:bold;
}

.back{
    margin-top:10px;
    width:95%;
    background:#444;
    color:white;
}
</style>
</head>

<body>

<div class="box">

<h2>Changer mot de passe</h2>

<form method="POST">

<input type="password" name="password" placeholder="Nouveau mot de passe" required>

<button name="reset">Valider</button>

</form>

<p style="color:lightgreen;"><?php echo $msg; ?></p>

<!-- 🔙 BOUTON RETOUR -->
<form action="auth.php">
    <button type="submit" class="back">⬅ Retour</button>
</form>

</div>

</body>
</html>