<?php
header("Location: auth.php");
exit;
include("conf.php");

if ($conn) {
echo "Connexion OK";
}
?>
