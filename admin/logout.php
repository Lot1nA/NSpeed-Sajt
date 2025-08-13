<?php
// admin/logout.php - Odjava administratora
session_start(); // Pokretanje sesije
session_unset(); // Uklanjanje svih sesijskih varijabli
session_destroy(); // Uništenje sesije

header('Location: login.php'); // Preusmeravanje na stranicu za prijavu
exit;
?>
