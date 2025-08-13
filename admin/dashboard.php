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
    <!-- Flatpickr CSS for Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

        /* Flatpickr Custom Styling for Admin Dashboard */
        .flatpickr-calendar {
            background: #1F2937; /* gray-800 */
            border: 1px solid #374151; /* gray-700 */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            color: #F3F4F6; /* gray-100 */
            width: 100%; /* Da zauzme celu širinu */
            max-width: none; /* Ukloni max-width */
        }
        .flatpickr-months .flatpickr-month {
            background-color: #1F2937; /* gray-800 */
            color: #F3F4F6; /* gray-100 */
        }
        .flatpickr-current-month .flatpickr-numInputWrapper span.arrowUp:after,
        .flatpickr-current-month .flatpickr-numInputWrapper span.arrowDown:after {
            color: #F3F4F6; /* gray-100 */
        }
        .flatpickr-weekdays {
            background-color: #1F2937; /* gray-800 */
        }
        .flatpickr-weekday {
            color: #D1D5DB; /* gray-300 */
        }
        .flatpickr-day {
            color: #F3F4F6; /* gray-100 */
            transition: background-color 0.2s, color 0.2s;
        }
        .flatpickr-day.today {
            border-color: #F97316; /* orange-500 */
            border-width: 2px; /* Deblji border za danas */
            background-color: rgba(249, 115, 22, 0.1); /* Blaga pozadina za danas */
        }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover,
        .flatpickr-day.selected.prevMonth,
        .flatpickr-day.selected.nextMonth,
        .flatpickr-day.startRange.prevMonth,
        .flatpickr-day.startRange.nextMonth,
        .flatpickr-day.endRange.prevMonth,
        .flatpickr-day.endRange.nextMonth {
            background: #F97316; /* orange-500 */
            border-color: #F97316;
            color: #FFF;
        }
        .flatpickr-day.inRange {
            background-color: rgba(249, 115, 22, 0.2); /* orange-500 transparent */
            border-color: rgba(249, 115, 22, 0.2);
            box-shadow: none;
        }
        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            color: #6B7280; /* gray-500 */
            cursor: not-allowed;
        }
        .flatpickr-day.prevMonth, .flatpickr-day.nextMonth {
            opacity: 0.6;
        }
        .flatpickr-day:hover {
            background-color: #374151; /* gray-700 */
        }
        .flatpickr-current-month .flatpickr-numInputWrapper:hover .flatpickr-numInput,
        .flatpickr-current-month .flatpickr-numInputWrapper span.arrowUp:hover,
        .flatpickr-current-month .flatpickr-numInputWrapper span.arrowDown:hover {
            color: #F97316; /* orange-500 */
        }
        .numInputWrapper span.arrowUp:after {
            border-bottom-color: #F3F4F6;
        }
        .numInputWrapper span.arrowDown:after {
            border-top-color: #F3F4F6;
        }

        /* Stil za detalje rezervacije ispod kalendara */
        #calendarDetails {
            margin-top: 20px;
            background-color: #1F2937; /* gray-800 */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        #calendarDetails h3 {
            color: #F3F4F6;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        #calendarDetails ul {
            list-style: none;
            padding: 0;
        }
        #calendarDetails ul li {
            background-color: #374151; /* gray-700 */
            padding: 10px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        #calendarDetails ul li strong {
            color: #F97316; /* orange-500 */
        }
        #calendarDetails ul li span {
            color: #D1D5DB; /* gray-300 */
        }
        #calendarDetails .no-bookings-message {
            color: #D1D5DB;
            text-align: center;
            padding: 20px;
        }

        /* Custom style for booked dates in Flatpickr */
        /* Obeležavanje datuma sa rezervacijama */
        .flatpickr-day.booked-date {
            background-color: rgba(249, 115, 22, 0.2) !important; /* Blago narandžasta pozadina */
            border: 2px solid #F97316 !important; /* Narandžasti border */
            color: #F3F4F6 !important; /* Beli tekst */
            font-weight: bold !important;
            border-radius: 4px !important; /* Blago zaobljeni uglovi */
        }
        .flatpickr-day.booked-date:hover {
            background-color: #F97316 !important; /* Potpuno narandžasta na hover */
            color: #FFF !important; /* Beli tekst na hover */
        }
        .flatpickr-day.booked-date.selected {
            background-color: #F97316 !important; /* Osiguraj da ostane narandžasta kada je selektovana */
            color: #FFF !important;
        }
    </style>
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
            <button class="admin-tab-button" data-tab="calendar-view-tab">Kalendarski Pregled</button>
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
                                <th>Trajanje</th>
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
                                <td><?= htmlspecialchars($res['duration']) ?> min</td>
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

        <!-- Novi tab za kalendarski pregled -->
        <div id="calendar-view-tab" class="admin-tab-content hidden">
            <h2 class="text-2xl font-semibold text-gray-200 mb-4">Kalendarski Pregled Rezervacija</h2>
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <input type="text" id="adminCalendar" placeholder="Učitavanje kalendara..." class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3 hidden">
                <div id="calendarContainer" class="w-full"></div>
            </div>
            <p class="text-gray-400 text-sm mt-4">Datumi sa potvrdjenim rezervacijama su obeleženi. Kliknite na datum za detalje.</p>
            
            <div id="calendarDetails" class="hidden">
                <h3 class="text-xl font-semibold text-white mb-4">Rezervacije za <span id="selectedCalendarDate"></span></h3>
                <ul id="dayBookingsList" class="space-y-3">
                    <!-- Detalji rezervacija za izabrani dan će se ovde učitavati -->
                </ul>
                <p id="noBookingsMessage" class="no-bookings-message hidden">Nema rezervacija za izabrani datum.</p>
            </div>
        </div>

    </div>

    <!-- Flatpickr JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.admin-tab-button');
            const tabContents = document.querySelectorAll('.admin-tab-content');
            const adminCalendarInput = document.getElementById('adminCalendar');
            const calendarContainer = document.getElementById('calendarContainer');
            const calendarDetails = document.getElementById('calendarDetails');
            const selectedCalendarDate = document.getElementById('selectedCalendarDate');
            const dayBookingsList = document.getElementById('dayBookingsList');
            const noBookingsMessage = document.getElementById('noBookingsMessage');

            let adminFlatpickrInstance; // Instanca kalendara
            let bookedDates = []; // Niz zauzetih datuma za Flatpickr

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

                    // Inicijalizuj kalendar samo kada se aktivira tab "calendar-view-tab"
                    if (targetTab === 'calendar-view-tab') {
                        if (!adminFlatpickrInstance) { // Inicijalizuj samo jednom
                            fetchBookedDatesForCalendar();
                        }
                    } else {
                        // Sakrij detalje rezervacija kada se prebaci na drugi tab
                        calendarDetails.classList.add('hidden');
                    }
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

            // Funkcija za dohvatanje svih zauzetih datuma za kalendar
            function fetchBookedDatesForCalendar() {
                fetch('../api/get_all_bookings_for_calendar.php') // Putanja do API endpointa
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            bookedDates = data.data; // Sacuvaj zauzete datume
                            // Inicijalizacija Flatpickr kalendara
                            adminFlatpickrInstance = flatpickr(calendarContainer, { // Inicijalizacija na kontejneru
                                inline: true, // Prikazuje kalendar uvek otvorenim
                                mode: "single", // Omogućava odabir samo jednog datuma
                                dateFormat: "Y-m-d",
                                static: true, // Sprečava da se kalendar pomera sa skrolom
                                disable: [
                                    // Primer: onemogući datume pre danas
                                    {
                                        from: "1900-01-01", 
                                        to: new Date().toISOString().slice(0,10) 
                                    }
                                ],
                                // Koristimo onDayCreate za dodavanje klase zauzetim datumima
                                onDayCreate: function(dObj, dStr, fp, dayElem) {
                                    if (bookedDates.includes(dStr)) {
                                        dayElem.classList.add('booked-date');
                                    }
                                },
                                // Kada se izabere datum u kalendaru
                                onChange: function(selectedDates, dateStr, instance) {
                                    if (selectedDates.length > 0) {
                                        fetchDayBookings(dateStr);
                                    } else {
                                        calendarDetails.classList.add('hidden');
                                    }
                                }
                            });
                        } else {
                            console.error('Greška pri dohvatanju datuma za kalendar:', data.message);
                            calendarContainer.innerHTML = '<p class="text-red-400">Greška pri učitavanju kalendara: ' + (data.message || 'Nepoznata greška') + '</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Mrežna greška pri dohvatanju datuma za kalendar:', error);
                        calendarContainer.innerHTML = '<p class="text-red-400">Mrežna greška pri učitavanju kalendara.</p>';
                    });
            }

            // Funkcija za dohvatanje detalja rezervacija za izabrani dan
            function fetchDayBookings(date) {
                selectedCalendarDate.textContent = new Date(date).toLocaleDateString('sr-RS', { day: 'numeric', month: 'long', year: 'numeric' });
                dayBookingsList.innerHTML = ''; // Ocisti prethodne detalje
                noBookingsMessage.classList.add('hidden'); // Sakrij poruku o praznim rezervacijama

                fetch('../api/get_day_bookings.php?date=' + encodeURIComponent(date))
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            if (data.data.length > 0) {
                                data.data.forEach(booking => {
                                    const listItem = document.createElement('li');
                                    let simulatorInfo = [];
                                    if (booking.standard_quantity > 0) {
                                        simulatorInfo.push(`Standardni: ${booking.standard_quantity}`);
                                    }
                                    if (booking.pro_quantity > 0) {
                                        simulatorInfo.push(`Pro: ${booking.pro_quantity}`);
                                    }
                                    const simulators = simulatorInfo.join(', ') || 'N/A';

                                    listItem.innerHTML = `
                                        <div>
                                            <strong>${booking.reservation_time}</strong> (${booking.duration} min) - ${simulators}
                                            <br>
                                            <span>${htmlspecialchars(booking.name)} (${htmlspecialchars(booking.email)})</span>
                                            <span class="status-${htmlspecialchars(booking.status)} ml-2">${ucfirst(htmlspecialchars(booking.status))}</span>
                                        </div>
                                        <div class="text-right">
                                            <form action="dashboard.php" method="POST" class="inline-block mr-2">
                                                <input type="hidden" name="reservation_id" value="${booking.id}">
                                                <input type="hidden" name="action" value="confirm">
                                                <input type="hidden" name="action_type" value="fixed_booking">
                                                <button type="submit" class="action-btn confirm-btn" ${ (booking.status === 'confirmed') ? 'disabled' : '' }>Potvrdi</button>
                                            </form>
                                            <form action="dashboard.php" method="POST" class="inline-block">
                                                <input type="hidden" name="reservation_id" value="${booking.id}">
                                                <input type="hidden" name="action" value="cancel">
                                                <input type="hidden" name="action_type" value="fixed_booking">
                                                <button type="submit" class="action-btn cancel-btn" ${ (booking.status === 'cancelled') ? 'disabled' : '' }>Otkaži</button>
                                            </form>
                                        </div>
                                    `;
                                    dayBookingsList.appendChild(listItem);
                                });
                                calendarDetails.classList.remove('hidden');
                            } else {
                                noBookingsMessage.classList.remove('hidden');
                                calendarDetails.classList.remove('hidden');
                            }
                        } else {
                            console.error('Greška pri dohvatanju detalja za dan:', data.message);
                            dayBookingsList.innerHTML = '<li class="text-red-400">Greška pri učitavanju detalja rezervacija.</li>';
                            calendarDetails.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Mrežna greška pri dohvatanju detalja za dan:', error);
                        dayBookingsList.innerHTML = '<li class="text-red-400">Mrežna greška pri učitavanju detalja rezervacija.</li>';
                        calendarDetails.classList.remove('hidden');
                    });
            }

            // Pomocne funkcije za htmlspecialchars i ucfirst (kao u PHP-u)
            function htmlspecialchars(str) {
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return str.replace(/[&<>"']/g, function(m) { return map[m]; });
            }

            function ucfirst(str) {
                if (typeof str !== 'string' || str.length === 0) {
                    return '';
                }
                return str.charAt(0).toUpperCase() + str.slice(1);
            }
        });
    </script>
</body>
</html>
```

