<?php
session_start();
session_unset(); // supprimer variables
session_destroy(); // détruire session

header("Location: auth.php");
exit();
?>