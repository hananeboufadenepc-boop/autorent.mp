<?php
include "config.php";

if(isset($_POST['register'])) {

    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO client(nom,email,motDePasse)
            VALUES('$nom','$email','$password')";

    $conn->query($sql);

    echo "Compte créé avec succès";
}
?>

<form method="POST">
<input name="nom" placeholder="Nom">
<input name="email" placeholder="Email">
<input name="password" placeholder="Password">
<button name="register">S'inscrire</button>
</form>