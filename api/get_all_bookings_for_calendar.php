<?php
// api/get_all_bookings_for_calendar.php - Preuzima sve potvrđene rezervacije za fiksne simulatore za prikaz u kalendaru
header('Content-Type: application/json');
require_once '../config.php';

$response = [
    'success' => false,
    'data' => [], // Sadržaće datume za kalendar
    'message' => ''
];

try {
    // Dohvatanje fiksnih rezervacija
    // Vracamo samo DISTINCT reservation_date za potvrđene rezervacije
    $stmt_fixed = $pdo->query("SELECT DISTINCT reservation_date FROM reservations WHERE status = 'confirmed'");
    $fixed_dates = $stmt_fixed->fetchAll(PDO::FETCH_COLUMN); // Dohvata samo kolonu reservation_date

    // Uklanjamo mobilne rezervacije iz ovog API-ja, jer je zahtev da kalendar prikazuje samo fiksne
    // $stmt_mobile = $pdo->query("SELECT start_date, end_date FROM mobile_simulator_rentals WHERE status = 'confirmed'");
    // $mobile_ranges = $stmt_mobile->fetchAll(PDO::FETCH_ASSOC);

    $booked_dates = [];

    // Dodaj fiksne datume
    foreach ($fixed_dates as $date) {
        $booked_dates[] = $date;
    }

    // Nema potrebe za dodavanjem mobilnih rezervacija ovde, jer zelimo samo fiksne
    /*
    foreach ($mobile_ranges as $range) {
        $start = new DateTime($range['start_date']);
        $end = new DateTime($range['end_date']);
        
        for ($i = $start; $i <= $end; $i->modify('+1 day')) {
            $booked_dates[] = $i->format('Y-m-d');
        }
    }
    */

    // Ukloni duplikate i sortiraj datume
    $booked_dates = array_unique($booked_dates);
    sort($booked_dates);

    $response['data'] = $booked_dates;
    $response['success'] = true;

} catch (PDOException $e) {
    $response['message'] = 'Greška pri dohvatanju svih rezervacija za kalendar: ' . $e->getMessage();
}

echo json_encode($response);
?>
