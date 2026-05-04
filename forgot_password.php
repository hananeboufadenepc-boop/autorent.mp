<?php
session_start();
include "config.php";

$msg = "";

if (isset($_POST['send'])) {

    $email = $_POST['email'];

    $res = $conn->query("SELECT * FROM client WHERE email='$email'");

    if ($res && $res->num_rows > 0) {

        $_SESSION['reset_email'] = $email;

        header("Location: reset_password.php");
        exit();

    } else {
        $msg = "❌ Email introuvable";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Mot de passe oublié</title>

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

<h2>Mot de passe oublié</h2>

<form method="POST">

<input type="email" name="email" placeholder="Ton email" required>

<button name="send">Envoyer</button>

</form>

<p style="color:red;"><?php echo $msg; ?></p>

<!-- 🔙 BOUTON RETOUR -->
<form action="auth.php">
    <button type="submit" class="back">⬅ Retour</button>
</form>

</div>

</body>
</html>