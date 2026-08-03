<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$file = 'messages.json';

// Cipta fail messages.json jika belum wujud
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

// BACA DATA (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_get_contents($file);
    exit;
}

// SIMPAN DATA (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['message']) && !empty(trim($input['message']))) {
        $existingData = json_decode(file_get_contents($file), true) ?? [];

        $newMessage = [
            'id' => uniqid(),
            'name' => !empty($input['name']) ? htmlspecialchars($input['name']) : 'Anon Galau',
            'message' => htmlspecialchars($input['message']),
            'time' => date('d M Y, h:i A')
        ];

        // Tambahkan mesej baru ke bahagian paling atas
        array_unshift($existingData, $newMessage);

        // Simpan semula ke dalam fail JSON
        file_put_contents($file, json_encode($existingData, JSON_PRETTY_PRINT));

        echo json_encode(['status' => 'success', 'message' => 'Berjaya dihantar!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Mesej kosong!']);
    }
    exit;
}
?>
