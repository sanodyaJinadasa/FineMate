<?php
// save_location.php
header('Content-Type: application/json');

// Basic auth / session check - require logged-in user
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error'=>'Not authenticated']);
    exit;
}

// Require JSON body
$raw = file_get_contents('php://input');
if (!$raw) {
    http_response_code(400);
    echo json_encode(['error'=>'Missing body']);
    exit;
}
$data = json_decode($raw, true);
if (!isset($data['latitude']) || !isset($data['longitude'])) {
    http_response_code(400);
    echo json_encode(['error'=>'Missing latitude/longitude']);
    exit;
}

$lat = floatval($data['latitude']);
$lng = floatval($data['longitude']);
$user_id = intval($_SESSION['user_id']);

$city = null;
$address = null;






$ua = 'FineMateApp/1.0 (sanodyav@gmail.com)';
$nom_url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&zoom=10&addressdetails=1";
$opts = stream_context_create(['http'=>['header'=>"User-Agent: {$ua}\r\n"]]);
$resp = @file_get_contents($nom_url, false, $opts);
if ($resp !== false) {
    $j = json_decode($resp, true);
    if (!empty($j['address'])) {
        $address = $j['display_name'] ?? null;
        $city = $j['address']['city'] ?? ($j['address']['town'] ?? ($j['address']['village'] ?? null));
    }
}


$host = 'localhost';
$db   = 'finemate';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';



$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error'=>'DB connection failed']);
    exit;
}

$pdo->exec("
CREATE TABLE IF NOT EXISTS user_locations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  latitude DOUBLE NOT NULL,
  longitude DOUBLE NOT NULL,
  city VARCHAR(255) DEFAULT NULL,
  address TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$stmt = $pdo->prepare("INSERT INTO user_locations (user_id, latitude, longitude, city, address) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $lat, $lng, $city, $address]);

echo json_encode(['success'=>true, 'city'=>$city, 'address'=>$address]);
