<?php
// api/make_mobile_booking.php - Obrađuje rezervacije za mobilne simulatore
header('Content-Type: application/json'); // Postavlja zaglavlje za JSON odgovor
require_once '../config.php'; // Povezivanje sa bazom podataka

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Uzimanje i validacija podataka
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? ''; // Opciono
    $startDate = $data['startDate'] ?? '';
    $endDate = $data['endDate'] ?? '';
    $location = $data['location'] ?? '';
    $notes = $data['notes'] ?? ''; // Opciono

    if (empty($name) || empty($email) || empty($startDate) || empty($endDate) || empty($location)) {
        $response['message'] = 'Molimo popunite sva obavezna polja za rezervaciju mobilnog simulatora.';
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Unesite ispravan format email adrese.';
        echo json_encode($response);
        exit;
    }

    // Provera da li je start_date pre end_date
    if (strtotime($startDate) > strtotime($endDate)) {
        $response['message'] = 'Datum početka ne može biti nakon datuma završetka.';
        echo json_encode($response);
        exit;
    }

    // Provera preklapanja termina (jednostavna provera za jedan simulator)
    // Napomena: Za više mobilnih simulatora, ovo bi zahtevalo složeniju logiku dostupnosti
    try {
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM mobile_simulator_rentals
                                      WHERE (start_date <= :endDate AND end_date >= :startDate)
                                      AND status = 'confirmed'"); // Proveravamo samo potvrđene rezervacije
        $stmt_check->execute([
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
        if ($stmt_check->fetchColumn() > 0) {
            $response['message'] = 'Mobilni simulator je već rezervisan u izabranom periodu. Molimo izaberite druge datume.';
            echo json_encode($response);
            exit;
        }
    } catch (PDOException $e) {
        $response['message'] = 'Greška pri proveri dostupnosti mobilnog simulatora: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }


    // Umetanje podataka u bazu
    try {
        $stmt = $pdo->prepare("INSERT INTO mobile_simulator_rentals (name, email, phone, start_date, end_date, location, notes, status)
                               VALUES (:name, :email, :phone, :start_date, :end_date, :location, :notes, 'pending')");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $location,
            'notes' => $notes
        ]);

        $response['success'] = true;
        $response['message'] = 'Vaša rezervacija mobilnog simulatora je uspešno poslata. Očekujte potvrdu putem emaila.';

    } catch (PDOException $e) {
        $response['message'] = 'Greška pri čuvanju rezervacije mobilnog simulatora: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Nevažeći metod zahteva.';
}

echo json_encode($response);
?>
