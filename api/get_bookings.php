<?php
// api/get_bookings.php - Preuzima zauzete termine za dati datum i vraca detalje o dostupnosti
header('Content-Type: application/json'); // Postavlja zaglavlje za JSON odgovor
require_once '../config.php'; // Povezivanje sa bazom podataka

$date = $_GET['date'] ?? ''; // Uzima datum iz GET parametra

$response = [
    'success' => false,
    'data' => [], // Sadrzace detalje o dostupnosti za svaki slot
    'message' => ''
];

// Definisanje ukupnog broja simulatora
$total_standard_simulators = 4;
$total_pro_simulators = 2;

// Svi moguci vremenski slotovi (od 10:00 do 22:00, svakih 30 minuta)
$all_time_slots = [];
for ($h = 10; $h <= 22; $h++) {
    if ($h < 22) { // Dozvoljava 22:00, ali ne 22:30
        $all_time_slots[] = sprintf('%02d:00', $h);
        $all_time_slots[] = sprintf('%02d:30', $h);
    } else { // Samo 22:00, bez 22:30
        $all_time_slots[] = sprintf('%02d:00', $h);
    }
}

// Inicijalizacija podataka o dostupnosti za svaki slot
$availability = [];
foreach ($all_time_slots as $slot) {
    $availability[$slot] = [
        'standard_total' => $total_standard_simulators,
        'pro_total' => $total_pro_simulators,
        'standard_booked' => 0,
        'pro_booked' => 0
    ];
}


// Provera da li je datum validan
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
    $response['message'] = 'Nevažeći format datuma.';
    echo json_encode($response);
    exit;
}

try {
    // Dohvatanje potvrđenih rezervacija za dati datum
    // SADA SUMIRAMO KOLIČINE ZA SVAKI TIP SIMULATORA PO VREMENSKOM SLOTU
    $stmt = $pdo->prepare("SELECT reservation_time, SUM(standard_quantity) as booked_standard, SUM(pro_quantity) as booked_pro FROM reservations WHERE reservation_date = :date AND status = 'confirmed' GROUP BY reservation_time");
    $stmt->execute(['date' => $date]);
    $booked_slots_summary = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Popunjavanje podataka o zauzetosti
    foreach ($booked_slots_summary as $booked_slot) {
        $time = $booked_slot['reservation_time'];
        
        if (isset($availability[$time])) {
            $availability[$time]['standard_booked'] = $booked_slot['booked_standard'] ?? 0;
            $availability[$time]['pro_booked'] = $booked_slot['booked_pro'] ?? 0;
        }
    }

    // Pretvaranje asocijativnog niza u indeksirani niz za lakše parsiranje na frontendu
    foreach ($availability as $time_slot => $details) {
        $response['data'][] = array_merge(['time' => $time_slot], $details);
    }

    $response['success'] = true;

} catch (PDOException $e) {
    $response['message'] = 'Greška pri dohvatanju podataka o dostupnosti: ' . $e->getMessage();
}

echo json_encode($response); // Slanje JSON odgovora
?>
