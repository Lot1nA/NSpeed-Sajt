<?php
// generate_hash.php - Fajl za generisanje heša lozinke

$password = 'nspeed'; // Lozinka koju želite da hešujete
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Lozinka: " . $password . "<br>";
echo "Generisani heš: " . $hashed_password . "<br>";
echo "Zalepite ovaj heš u SQL komandu za 'nspeed' korisnika u admin_users tabeli.";
?>
