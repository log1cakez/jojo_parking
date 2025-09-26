<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration for jojo database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jojo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['slot1']) && isset($input['slot2']) && isset($input['slot3'])) {
        // Update parking slots status
        updateParkingSlot($conn, 1, $input['slot1']);
        updateParkingSlot($conn, 2, $input['slot2']);
        updateParkingSlot($conn, 3, $input['slot3']);
        
        // Update gate status if provided
        if (isset($input['entrance_gate'])) {
            updateGateStatus($conn, 'entrance_gate', $input['entrance_gate']);
        }
        if (isset($input['exit_gate'])) {
            updateGateStatus($conn, 'exit_gate', $input['exit_gate']);
        }
        
        echo json_encode(["status" => "success", "message" => "Parking status updated"]);
        
    } elseif (isset($input['card_uid']) && isset($input['action'])) {
        // Log access attempt
        $card_uid = $conn->real_escape_string($input['card_uid']);
        $action = $conn->real_escape_string($input['action']);
        $status = isset($input['status']) ? $conn->real_escape_string($input['status']) : 'unknown';
        
        $sql = "INSERT INTO access_logs (card_uid, action, status) VALUES ('$card_uid', '$action', '$status')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Access logged"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error logging access"]);
        }
        
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid data"]);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get current system status
    $response = [];
    
    // Get parking slots status
    $result = $conn->query("SELECT * FROM parking_slots ORDER BY slot_number");
    $response['slots'] = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $response['slots'][] = $row;
        }
    }
    
    // Get gate status
    $result = $conn->query("SELECT * FROM system_status ORDER BY id DESC LIMIT 1");
    $response['gates'] = $result ? $result->fetch_assoc() : ['entrance_gate' => 'closed', 'exit_gate' => 'closed'];
    
    // Get recent access logs
    $result = $conn->query("SELECT * FROM access_logs ORDER BY timestamp DESC LIMIT 10");
    $response['logs'] = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $response['logs'][] = $row;
        }
    }
    
    // Get statistics
    $result = $conn->query("SELECT COUNT(*) as total_slots FROM parking_slots");
    $response['stats']['total_slots'] = $result ? $result->fetch_assoc()['total_slots'] : 0;
    
    $result = $conn->query("SELECT COUNT(*) as occupied_slots FROM parking_slots WHERE status = 'occupied'");
    $response['stats']['occupied_slots'] = $result ? $result->fetch_assoc()['occupied_slots'] : 0;
    
    $result = $conn->query("SELECT COUNT(*) as available_slots FROM parking_slots WHERE status = 'empty'");
    $response['stats']['available_slots'] = $result ? $result->fetch_assoc()['available_slots'] : 0;
    
    echo json_encode($response);
}

function updateParkingSlot($conn, $slotNumber, $status) {
    $status = $conn->real_escape_string($status);
    $sql = "UPDATE parking_slots SET status = '$status' WHERE slot_number = $slotNumber";
    $conn->query($sql);
}

function updateGateStatus($conn, $gate, $status) {
    $status = $conn->real_escape_string($status);
    $gate = $conn->real_escape_string($gate);
    $sql = "UPDATE system_status SET $gate = '$status' ORDER BY id DESC LIMIT 1";
    $conn->query($sql);
}

$conn->close();
?>  