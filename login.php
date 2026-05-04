<?php
session_start();
include "config.php";

$message = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM client WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($password == $user['motDePasse']) {
            $_SESSION['client'] = $user['nom'];
            $message = "Connexion réussie ✔ Bienvenue " . $user['nom'];
        } else {
            $message = "Mot de passe incorrect ❌";
        }
    } else {
        $message = "Email introuvable ❌";
    }
}
?>

<h2>Login</h2>

<form method="POST">
    <input type="email" name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <button name="login">Se connecter</button>
</form>

<p><?php echo $message; ?></p>