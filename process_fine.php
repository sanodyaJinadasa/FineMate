<?php
session_start();
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'officer') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

$officer_id = $_SESSION['user_id'];

$offender_name = trim($_POST['offender_name'] ?? '');
$offender_nic = trim($_POST['offender_nic'] ?? '');
$offender_license_no = trim($_POST['offender_license_no'] ?? '');
$vehicle_no = trim($_POST['vehicle_no'] ?? '');
$fine_type = trim($_POST['fine_type'] ?? '');
$fine_amount = floatval($_POST['fine_amount'] ?? 0);
$fine_date = $_POST['fine_date'] ?? '';
$fine_time = $_POST['fine_time'] ?? '';
$fine_location = trim($_POST['fine_location'] ?? '');
$weather = trim($_POST['weather'] ?? '');
$description = trim($_POST['description'] ?? '');
$payment_status = $_POST['payment_status'] ?? 'Pending';
$due_date = $_POST['due_date'] ?? null;
$remarks = trim($_POST['remarks'] ?? '');
$offender_mobile = trim($_POST['offender_mobile'] ?? '');

$offender_mobile = preg_replace('/[^0-9]/', '', $offender_mobile); // remove non-digits
if (str_starts_with($offender_mobile, '0')) {
    $offender_mobile = '+94' . substr($offender_mobile, 1);
} elseif (!str_starts_with($offender_mobile, '94')) {
    $offender_mobile = '+94' . $offender_mobile;
} else {
    $offender_mobile = '+' . $offender_mobile;
}


$errors = [];
if ($offender_name === '')
    $errors[] = 'Offender name is required.';
if ($fine_type === '')
    $errors[] = 'Fine type is required.';
if ($fine_amount <= 0)
    $errors[] = 'Fine amount must be greater than 0.';
if ($fine_date === '')
    $errors[] = 'Fine date is required.';
if ($fine_time === '')
    $errors[] = 'Fine time is required.';

if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'message' => $errors[0]]);
    exit;
}

try {
    $pdo->beginTransaction();

    // Insert fine
    $stmt = $pdo->prepare("
        INSERT INTO fines (
            officer_id, offender_name, offender_nic,
            offender_license_no, vehicle_no, fine_type, fine_amount,
            fine_date, fine_time, fine_location, weather, description,
            payment_status, due_date, remarks
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $officer_id,
        $offender_name,
        $offender_nic,
        $offender_license_no,
        $vehicle_no,
        $fine_type,
        $fine_amount,
        $fine_date,
        $fine_time,
        $fine_location,
        $weather,
        $description,
        $payment_status,
        $due_date,
        $remarks
    ]);

    $stmt2 = $pdo->prepare("UPDATE drivers SET total_points = total_points - 10 WHERE nic = ?");
    $stmt2->execute([$offender_nic]);

    $pdo->commit();

    // if (!empty($offender_mobile)) {
    //     $smsText = "Dear $offender_name, you have received a fine.\n";
    //     $smsText .= "Type: $fine_type\n";
    //     $smsText .= "Amount: LKR $fine_amount\n";
    //     $smsText .= "Date: $fine_date\n";
    //     $smsText .= "Location: $fine_location";

    //     require __DIR__ . '/vendor/autoload.php';


    //     // Configuration
    //     $configuration = new Configuration(
    //         host: 'https://51wymj.api.infobip.com',
    //         apiKey: '747414b82ba0afef49beb3bb28066981-9e290b7d-2a7f-4481-a3fe-09a597a56db0'
    //     );

    //     $smsApi = new SmsApi(config: $configuration);

    //     // Recipient and message
    //     $offender_mobile = '+94' . ltrim($offender_mobile, '+94');
    //     // Create destination
    //     $destination = new SmsDestination(to: $offender_mobile);

    //     // Create SMS textual message
    //     $message = new SmsTextualMessage(
    //         destinations: [$destination],
    //         text: $smsText
    //     );

    //     // Create SMS request
    //     $request = new SmsAdvancedTextualRequest(messages: [$message]);

    //     try {
    //         $response = $smsApi->sendSmsMessage($request);
    //         // echo "SMS sent successfully!\n";
    //         // print_r($response);
    //     } catch (\Infobip\ApiException $e) {
    //         // echo "SMS Error: " . $e->getMessage() . "\n";
    //         // echo "Response body: " . $e->getResponseBody();
    //     } catch (\Exception $e) {
    //         // echo "General Error: " . $e->getMessage();
    //     }
    // }

    echo json_encode(['status' => 'success', 'message' => 'Fine Added Successfully.']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Error saving fine: ' . $e->getMessage()]);
}
?>