<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "config.php";

if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

/* ================= LOGIN ================= */
if (isset($_POST['login'])) {
    $msg = "";
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM client WHERE email='$email'");

    if ($res && $res->num_rows > 0) {

        $user = $res->fetch_assoc();

        if ($password == $user['motDePasse']) {

            $_SESSION['attempts'] = 0;
            $_SESSION['user'] = $user['nom'];

            // 🔥 logique simple admin/client
            if ($email === "admin@gmail.com") {
                $_SESSION['role'] = 'admin';
                header("Location: admin_panel.php");
            } else {
                $_SESSION['role'] = 'client';
                header("Location: dashboard.php");
            }

            exit();

        } else {
            $_SESSION['attempts']++;

if ($_SESSION['attempts'] >= 3) {
    $msg = "❌ Mot de passe incorrect <br>
    <a href='forgot_password.php' style='color:gold;'>🔑 Mot de passe oublié ?</a>";
} else {
    $msg = "❌ Mot de passe incorrect";
}
        }

    } else {
        $_SESSION['attempts']++;
        
if ($_SESSION['attempts'] >= 3) {
    $msg = "❌ Email introuvable <br>
    <a href='forgot_password.php' style='color:gold;'>🔑 Mot de passe oublié ?</a>";
} else {
    $msg = "❌ Email introuvable";
}
    }
}

/* ================= REGISTER ================= */
if (isset($_POST['register'])) {

    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ✔ client par défaut
    $conn->query("
        INSERT INTO client(nom,email,motDePasse,role)
        VALUES('$nom','$email','$password','client')
    ");

    $msg = "✔ Compte créé avec succès";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>AutoRent Luxe Connexion</title>

<style>
body{
    margin:0;
    font-family:Arial;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
    background:url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1400&q=80')
    no-repeat center/cover;
}

.overlay{
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.75);
    z-index:0;
}

.container{
    position:relative;
    width:420px;
    padding:30px;
    background:rgba(255,255,255,0.05);
    backdrop-filter:blur(18px);
    border-radius:18px;
    border:1px solid gold;
    z-index:1;
}

.logo-box{text-align:center;margin-bottom:15px;}
.logo-box img{width:80px;height:80px;border-radius:50%;border:2px solid gold;}
.logo-text{color:gold;font-size:26px;font-weight:bold;}
.system{text-align:center;font-size:12px;color:#aaa;margin-bottom:15px;}
.msg{text-align:center;color:lightgreen;}

.switch{display:flex;gap:10px;margin-bottom:15px;}
.switch button{flex:1;padding:10px;border:none;border-radius:10px;background:rgba(255,255,255,0.1);color:white;}
.switch button:hover{background:gold;color:black;}

.form{display:none;}
.active{display:block;}

input,select{
    width:95%;
    padding:12px;
    margin:8px 0;
    border:none;
    border-radius:10px;
    background:rgba(255,255,255,0.08);
    color:white;
}

button.submit{
    width:100%;
    padding:12px;
    margin-top:10px;
    background:gold;
    border:none;
    border-radius:10px;
    color:black;
    font-weight:bold;
    cursor:pointer;
}
select{
    width:100%;
    padding:12px;
    margin:8px 0;

    border:none;
    border-radius:10px;

    background:#111;   /* noir */
    color:white;       /* texte blanc */
    cursor:pointer;
}

/* option dans la liste */
select option{
    background:#111;
    color:white;
}
</style>

</head>

<body>

<div class="overlay"></div>

<div class="container">

<div class="logo-box">
    <img src="images/logo.png">
    <div class="logo-text">AutoRent</div>
</div>

<div class="system">Système de location de voitures de luxe</div>

<p class="msg"><?php echo isset($msg) ? $msg : ""; ?></p>

<div class="switch">
    <button onclick="showLogin()">Connexion</button>
    <button onclick="showRegister()">Inscription</button>
</div>

<!-- LOGIN -->
<div id="login" class="form active">

<form method="POST">

<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Mot de passe" required>



<button class="submit" name="login">Se connecter</button>

</form>

</div>

<!-- REGISTER -->
<div id="register" class="form">

<form method="POST">

<input type="text" name="nom" placeholder="Nom" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Mot de passe" required>

<button class="submit" name="register">Créer compte</button>

</form>

</div>

</div>

<script>
function showLogin(){
    document.getElementById("login").classList.add("active");
    document.getElementById("register").classList.remove("active");
}

function showRegister(){
    document.getElementById("register").classList.add("active");
    document.getElementById("login").classList.remove("active");
}
</script>

</body>
</html>