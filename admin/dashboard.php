<?php
// admin/dashboard.php - Glavna stranica admin panela sa listom rezervacija
require_once '../config.php'; // Povezivanje sa bazom podataka
session_start(); // Pokretanje sesije

// Provera da li je administrator prijavljen
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); // Ako nije prijavljen, preusmeri na stranicu za prijavu
    exit;
}

// Dohvatanje svih rezervacija fiksnih simulatora
try {
    // Dohvatamo standard_quantity, pro_quantity i duration
    $stmt_fixed = $pdo->query("SELECT id, name, email, phone, reservation_date, reservation_time, standard_quantity, pro_quantity, duration, status, created_at FROM reservations ORDER BY created_at DESC");
    $fixed_reservations = $stmt_fixed->fetchAll();
} catch (PDOException $e) {
    die("Greška pri dohvatanju rezervacija fiksnih simulatora: " . $e->getMessage());
}

// Dohvatanje svih rezervacija mobilnih simulatora
try {
    $stmt_mobile = $pdo->query("SELECT * FROM mobile_simulator_rentals ORDER BY created_at DESC");
    $mobile_reservations = $stmt_mobile->fetchAll();
} catch (PDOException $e) {
    die("Greška pri dohvatanju rezervacija mobilnih simulatora: " . $e->getMessage());
}

// Logika za promenu statusa rezervacije (potvrda/otkazivanje) - FIKSNI SIMULATORI
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['action_type']) && $_POST['action_type'] === 'fixed_booking') {
    $reservation_id = $_POST['reservation_id'];
    $action = $_POST['action']; // 'confirm' ili 'cancel'

    $new_status = '';
    if ($action === 'confirm') {
        $new_status = 'confirmed';
    } elseif ($action === 'cancel') {
        $new_status = 'cancelled';
    }

    if ($new_status) {
        try {
            $stmt = $pdo->prepare("UPDATE reservations SET status = :status WHERE id = :id");
            $stmt->execute(['status' => $new_status, 'id' => $reservation_id]);
            header('Location: dashboard.php'); // Osvezi stranicu nakon promene
            exit;
        } catch (PDOException $e) {
            die("Greška pri ažuriranju statusa rezervacije fiksnog simulatora: " . $e->getMessage());
        }
    }
}

// Logika za promenu statusa rezervacije (potvrda/otkazivanje) - MOBILNI SIMULATORI
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rental_id'], $_POST['action_type']) && $_POST['action_type'] === 'mobile_rental') {
    $rental_id = $_POST['rental_id'];
    $action = $_POST['action']; // 'confirm' ili 'cancel'

    $new_status = '';
    if ($action === 'confirm') {
        $new_status = 'confirmed';
    } elseif ($action === 'cancel') {
        $new_status = 'cancelled';
    }

    if ($new_status) {
        try {
            $stmt = $pdo->prepare("UPDATE mobile_simulator_rentals SET status = :status WHERE id = :id");
            $stmt->execute(['status' => $new_status, 'id' => $rental_id]);
            header('Location: dashboard.php#mobile-rentals-tab'); // Osvezi stranicu i skroluj do sekcije
            exit;
        } catch (PDOException $e) {
            die("Greška pri ažuriranju statusa rezervacije mobilnog simulatora: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Rezervacije</title>
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
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #374151; }
        th { background-color: #1F2937; color: #D1D5DB; font-weight: 600; }
        td { background-color: #1F2937; }
        tr:hover td { background-color: #2D3748; }
        .status-pending { color: #F59E0B; } /* Amber-500 */
        .status-confirmed { color: #10B981; } /* Green-500 */
        .status-cancelled { color: #EF4444; } /* Red-500 */
        .action-btn {
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .confirm-btn { background-color: #10B981; color: white; }
        .confirm-btn:hover { background-color: #059669; }
        .cancel-btn { background-color: #EF4444; color: white; }
        .cancel-btn:hover { background-color: #DC2626; }

        /* Stilovi za tabove u admin panelu */
        .admin-tab-button {
            padding: 10px 20px;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
            color: #D1D5DB; /* gray-300 */
            background-color: #374151; /* gray-700 */
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
        }
        .admin-tab-button.active {
            background-color: #1F2937; /* gray-800 */
            color: #F97316; /* orange-500 */
        }
        .admin-tab-content {
            background-color: #1F2937; /* gray-800 */
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.admin-tab-button');
            const tabContents = document.querySelectorAll('.admin-tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;

                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    tabContents.forEach(content => {
                        if (content.id === targetTab) {
                            content.classList.remove('hidden');
                        } else {
                            content.classList.add('hidden');
                        }
                    });
                });
            });

            // Activate default tab on load (e.g., fixed-bookings-tab)
            const defaultTab = document.querySelector('.admin-tab-button[data-tab="fixed-bookings-tab"]');
            if (defaultTab) {
                defaultTab.click(); // Simulate click to activate
            }

            // If URL has a hash, activate that tab
            if (window.location.hash) {
                const hash = window.location.hash.substring(1); // Remove '#'
                const targetButton = document.querySelector(`.admin-tab-button[data-tab="${hash}"]`);
                if (targetButton) {
                    targetButton.click();
                }
            }
        });
    </script>
</head>
<body class="p-8">
    <div class="container mx-auto bg-gray-900 p-8 rounded-lg shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-bold gradient-text">Admin Panel</h1>
            <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full transition duration-300">Odjava</a>
        </div>

        <!-- Tab navigacija za admin panel -->
        <div class="flex border-b border-gray-700 mb-6">
            <button class="admin-tab-button active" data-tab="fixed-bookings-tab">Fiksni Simulatori</button>
            <button class="admin-tab-button" data-tab="mobile-rentals-tab">Mobilni Simulatori</button>
        </div>

        <!-- Sadržaj taba za fiksne simulatore -->
        <div id="fixed-bookings-tab" class="admin-tab-content">
            <h2 class="text-2xl font-semibold text-gray-200 mb-4">Rezervacije Fiksnih Simulatora</h2>
            
            <?php if (empty($fixed_reservations)): ?>
                <p class="text-gray-400">Trenutno nema rezervacija fiksnih simulatora.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ime</th>
                                <th>Email</th>
                                <th>Telefon</th>
                                <th>Datum</th>
                                <th>Vreme</th>
                                <th>Trajanje</th> <!-- Novo polje -->
                                <th>Standardni</th> 
                                <th>Pro</th>        
                                <th>Status</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fixed_reservations as $res): ?>
                            <tr>
                                <td><?= htmlspecialchars($res['id']) ?></td>
                                <td><?= htmlspecialchars($res['name']) ?></td>
                                <td><?= htmlspecialchars($res['email']) ?></td>
                                <td><?= htmlspecialchars($res['phone']) ?></td>
                                <td><?= date('d.m.Y', strtotime($res['reservation_date'])) ?></td>
                                <td><?= htmlspecialchars($res['reservation_time']) ?></td>
                                <td><?= htmlspecialchars($res['duration']) ?> min</td> <!-- Prikaz trajanja -->
                                <td><?= htmlspecialchars($res['standard_quantity']) ?></td> 
                                <td><?= htmlspecialchars($res['pro_quantity']) ?></td>    
                                <td>
                                    <span class="status-<?= htmlspecialchars($res['status']) ?>">
                                        <?= ucfirst(htmlspecialchars($res['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="dashboard.php" method="POST" class="inline-block mr-2">
                                        <input type="hidden" name="reservation_id" value="<?= $res['id'] ?>">
                                        <input type="hidden" name="action" value="confirm">
                                        <input type="hidden" name="action_type" value="fixed_booking">
                                        <button type="submit" class="action-btn confirm-btn" <?= ($res['status'] === 'confirmed') ? 'disabled' : '' ?>>Potvrdi</button>
                                    </form>
                                    <form action="dashboard.php" method="POST" class="inline-block">
                                        <input type="hidden" name="reservation_id" value="<?= $res['id'] ?>">
                                        <input type="hidden" name="action" value="cancel">
                                        <input type="hidden" name="action_type" value="fixed_booking">
                                        <button type="submit" class="action-btn cancel-btn" <?= ($res['status'] === 'cancelled') ? 'disabled' : '' ?>>Otkaži</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sadržaj taba za mobilne simulatore -->
        <div id="mobile-rentals-tab" class="admin-tab-content hidden">
            <h2 class="text-2xl font-semibold text-gray-200 mb-4">Rezervacije Mobilnih Simulatora</h2>
            
            <?php if (empty($mobile_reservations)): ?>
                <p class="text-gray-400">Trenutno nema rezervacija mobilnih simulatora.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ime</th>
                                <th>Email</th>
                                <th>Telefon</th>
                                <th>Od datuma</th>
                                <th>Do datuma</th>
                                <th>Lokacija</th>
                                <th>Napomene</th>
                                <th>Status</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mobile_reservations as $rental): ?>
                            <tr>
                                <td><?= htmlspecialchars($rental['id']) ?></td>
                                <td><?= htmlspecialchars($rental['name']) ?></td>
                                <td><?= htmlspecialchars($rental['email']) ?></td>
                                <td><?= htmlspecialchars($rental['phone']) ?></td>
                                <td><?= date('d.m.Y', strtotime($rental['start_date'])) ?></td>
                                <td><?= date('d.m.Y', strtotime($rental['end_date'])) ?></td>
                                <td><?= htmlspecialchars($rental['location']) ?></td>
                                <td><?= htmlspecialchars($rental['notes']) ?></td>
                                <td>
                                    <span class="status-<?= htmlspecialchars($rental['status']) ?>">
                                        <?= ucfirst(htmlspecialchars($rental['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="dashboard.php" method="POST" class="inline-block mr-2">
                                        <input type="hidden" name="rental_id" value="<?= $rental['id'] ?>">
                                        <input type="hidden" name="action" value="confirm">
                                        <input type="hidden" name="action_type" value="mobile_rental">
                                        <button type="submit" class="action-btn confirm-btn" <?= ($rental['status'] === 'confirmed') ? 'disabled' : '' ?>>Potvrdi</button>
                                    </form>
                                    <form action="dashboard.php" method="POST" class="inline-block">
                                        <input type="hidden" name="rental_id" value="<?= $rental['id'] ?>">
                                        <input type="hidden" name="action" value="cancel">
                                        <input type="hidden" name="action_type" value="mobile_rental">
                                        <button type="submit" class="action-btn cancel-btn" <?= ($rental['status'] === 'cancelled') ? 'disabled' : '' ?>>Otkaži</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
