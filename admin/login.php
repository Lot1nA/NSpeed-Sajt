<?php
// admin/login.php - Obrađuje prijavu administratora
require_once '../config.php'; // Povezivanje sa bazom podataka
session_start(); // Pokretanje sesije za praćenje stanja prijave

$error_message = '';

// Provera da li je forma za prijavu poslata
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? ''; // Uzimanje korisničkog imena iz forme
    $password = $_POST['password'] ?? ''; // Uzimanje lozinke iz forme

    // Pretraga korisnika u bazi
    $stmt = $pdo->prepare("SELECT id, username, password FROM admin_users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Provera da li korisnik postoji i da li je lozinka ispravna
    if ($user && password_verify($password, $user['password'])) {
        // Lozinka je ispravna, prijava uspešna
        $_SESSION['admin_logged_in'] = true; // Postavljanje sesije
        $_SESSION['admin_username'] = $user['username']; // Čuvanje korisničkog imena u sesiji
        header('Location: dashboard.php'); // Preusmeravanje na admin panel
        exit;
    } else {
        // Pogrešno korisničko ime ili lozinka
        $error_message = 'Pogrešno korisničko ime ili lozinka.';
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Prijava - NSPEED</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #111827; color: #F3F4F6; }
        .gradient-text {
            background: linear-gradient(to right, #F97316, #EF4444);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 p-8 rounded-lg shadow-2xl w-full max-w-md">
        <h2 class="text-3xl font-bold text-center mb-6">Admin <span class="gradient-text">Prijava</span></h2>

        <?php if ($error_message): ?>
            <div class="bg-red-500 text-white p-3 rounded-md mb-4 text-center">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-300 text-sm font-bold mb-2">Korisničko Ime:</label>
                <input type="text" id="username" name="username" class="shadow appearance-none border border-gray-600 rounded w-full py-3 px-4 bg-gray-700 text-gray-200 leading-tight focus:outline-none focus:shadow-outline focus:border-orange-500" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-300 text-sm font-bold mb-2">Lozinka:</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border border-gray-600 rounded w-full py-3 px-4 bg-gray-700 text-gray-200 leading-tight focus:outline-none focus:shadow-outline focus:border-orange-500" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-full focus:outline-none focus:shadow-outline transition duration-300 w-full">
                    Prijavi se
                </button>
            </div>
        </form>
    </div>
</body>
</html>
