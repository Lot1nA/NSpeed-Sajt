<?php
// api/make_booking.php - Obrađuje slanje formulara za rezervaciju i šalje email potvrdu
header('Content-Type: application/json'); // Postavlja zaglavlje za JSON odgovor
require_once '../config.php'; // Povezivanje sa bazom podataka

// Ukljucivanje PHPMailer biblioteke
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Putanja do PHPMailer fajlova (prilagodite ako ste ih drugacije postavili)
require '../includes/PHPMailer/Exception.php';
require '../includes/PHPMailer/PHPMailer.php';
require '../includes/PHPMailer/SMTP.php';

$response = [
    'success' => false,
    'message' => ''
];

// Funkcija za slanje emaila
function sendBookingConfirmationEmail($recipientEmail, $recipientName, $date, $time, $standardQty, $proQty) {
    $mail = new PHPMailer(true); // Omoguci izuzetke za detaljnije greske

    try {
        // Server settings (SMTP konfiguracija)
        // OVO MORATE PRILAGODITI SVOJIM SMTP PODACIMA!
        // Primer za Gmail SMTP:
        $mail->isSMTP();                                            // Posalji preko SMTP
        $mail->Host       = 'smtp.gmail.com';                       // SMTP server
        $mail->SMTPAuth   = true;                                   // Omoguci SMTP autentifikaciju
        $mail->Username   = 'vas_email@gmail.com';                  // SMTP korisnicko ime (vasa email adresa)
        $mail->Password   = 'VASA_EMAIL_LOZINKA_ILI_APP_LOZINKA';   // SMTP lozinka (ili app lozinka za Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Omoguci SSL/TLS enkripciju
        $mail->Port       = 465;                                    // TCP port za povezivanje (465 za SMTPS, 587 za TLS)

        // Recipient (Primaoci)
        $mail->setFrom('vas_email@gmail.com', 'NSPEED Sim Racing Centar'); // Od koga se salje
        $mail->addAddress($recipientEmail, $recipientName);         // Dodaj primaoca

        $simulatorDetails = [];
        if ($standardQty > 0) {
            $simulatorDetails[] = "Standardni: {$standardQty} komada";
        }
        if ($proQty > 0) {
            $simulatorDetails[] = "Pro: {$proQty} komada";
        }
        $simulatorText = implode(", ", $simulatorDetails);


        // Content (Sadrzaj emaila)
        $mail->isHTML(true);                                        // Format emaila kao HTML
        $mail->Subject = 'Potvrda rezervacije NSPEED simulatora';
        $mail->Body    = "
            <html>
            <head>
                <title>Potvrda Rezervacije</title>
                <style>
                    body { font-family: 'Inter', sans-serif; background-color: #f4f4f4; color: #333; }
                    .container { background-color: #fff; margin: 20px auto; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 600px; }
                    h1 { color: #F97316; }
                    p { line-height: 1.6; }
                    .details { background-color: #e9e9e9; padding: 15px; border-radius: 5px; margin-top: 20px; }
                    .footer { margin-top: 20px; font-size: 0.8em; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h1>Hvala na rezervaciji, {$recipientName}!</h1>
                    <p>Vaša rezervacija za simulator u NSPEED centru je primljena i čeka potvrdu.</p>
                    <div class='details'>
                        <p><strong>Datum:</strong> {$date}</p>
                        <p><strong>Vreme:</strong> {$time}</p>
                        <p><strong>Rezervisani simulatori:</strong> {$simulatorText}</p>
                    </div>
                    <p>Potvrdu vaše rezervacije očekujte uskoro. Za sva pitanja, kontaktirajte nas.</p>
                    <p class='footer'>Srdačan pozdrav,<br>NSPEED Tim</p>
                </div>
            </body>
            </html>
        ";
        $mail->AltBody = "Hvala na rezervaciji, {$recipientName}!\n\nVaša rezervacija za simulator u NSPEED centru je primljena i čeka potvrdu.\n\nDetalji rezervacije:\nDatum: {$date}\nVreme: {$time}\nRezervisani simulatori: {$simulatorText}\n\nPotvrdu vaše rezervacije očekujte uskoro. Za sva pitanja, kontaktirajte nas.\n\nSrdačan pozdrav,\nNSPEED Tim";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Logujte gresku, ali ne prikazujte je korisniku direktno
        error_log("Greška pri slanju emaila: {$mail->ErrorInfo}");
        return false;
    }
}


// Provera da li je zahtev poslat POST metodom
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Čitanje JSON ulaznih podataka
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validacija i uzimanje podataka iz POST zahteva
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? ''; 
    $date = $data['date'] ?? '';
    $time = $data['time'] ?? '';
    $standardQuantity = $data['standardQuantity'] ?? 0; // Kolicina standardnih
    $proQuantity = $data['proQuantity'] ?? 0;           // Kolicina pro

    // Jednostavna validacija
    if (empty($name) || empty($email) || empty($date) || empty($time) || ($standardQuantity == 0 && $proQuantity == 0)) {
        $response['message'] = 'Sva obavezna polja moraju biti popunjena i morate izabrati bar jedan simulator.';
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Unesite ispravan format email adrese.';
        echo json_encode($response);
        exit;
    }

    // Dohvatanje trenutne dostupnosti za server-side validaciju
    $total_standard_simulators = 4;
    $total_pro_simulators = 2;

    try {
        // Dohvatanje broja VEC POTVRDJENIH rezervacija za dati slot
        $stmt_check = $pdo->prepare("SELECT SUM(standard_quantity) as booked_standard, SUM(pro_quantity) as booked_pro FROM reservations WHERE reservation_date = :date AND reservation_time = :time AND status = 'confirmed'");
        $stmt_check->execute([
            'date' => $date,
            'time' => $time
        ]);
        $booked_counts = $stmt_check->fetch(PDO::FETCH_ASSOC); 

        $current_standard_booked = $booked_counts['booked_standard'] ?? 0;
        $current_pro_booked = $booked_counts['booked_pro'] ?? 0;

        $available_standard = $total_standard_simulators - $current_standard_booked;
        $available_pro = $total_pro_simulators - $current_pro_booked;

        // Provera da li je tražena količina dostupna
        if ($standardQuantity > $available_standard) {
            $response['message'] = 'Nema dovoljno standardnih simulatora za izabrani termin. Dostupno: ' . $available_standard;
            echo json_encode($response);
            exit;
        }
        if ($proQuantity > $available_pro) {
            $response['message'] = 'Nema dovoljno Pro simulatora za izabrani termin. Dostupno: ' . $available_pro;
            echo json_encode($response);
            exit;
        }

    } catch (PDOException $e) {
        $response['message'] = 'Greška pri proveri dostupnosti termina: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    // Umetanje podataka u bazu (jedan red sa kolicinama)
    try {
        $stmt_insert = $pdo->prepare("INSERT INTO reservations (name, email, phone, reservation_date, reservation_time, standard_quantity, pro_quantity, status) VALUES (:name, :email, :phone, :date, :time, :standard_quantity, :pro_quantity, 'pending')");
        $stmt_insert->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'date' => $date,
            'time' => $time,
            'standard_quantity' => $standardQuantity,
            'pro_quantity' => $proQuantity,
        ]);

        $response['success'] = true;
        $response['message'] = 'Vaša rezervacija je uspešno poslata. Očekujte potvrdu putem emaila.';

        // Slanje emaila korisniku
        if (!sendBookingConfirmationEmail($email, $name, $date, $time, $standardQuantity, $proQuantity)) {
            error_log("Neuspesno slanje emaila za rezervaciju. Korisnik: {$email}");
            $response['message'] .= " (Ali nismo uspeli da pošaljemo email potvrde. Proverite spam folder.)";
        }

    } catch (PDOException $e) {
        $response['message'] = 'Greška pri čuvanju rezervacije: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Nevažeći metod zahteva.';
}

echo json_encode($response); // Slanje JSON odgovora
?>
