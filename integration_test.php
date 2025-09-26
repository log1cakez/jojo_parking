<?php
/**
 * Integration Test Script for JoJo Smart Parking System
 * This script tests the API endpoints and database integration
 */

// Test configuration
$base_url = "http://localhost"; // Adjust this to your server URL
$api_url = $base_url . "/parking_api.php";

echo "=== JoJo Smart Parking System Integration Test ===\n\n";

// Test 1: Check if API is accessible
echo "1. Testing API accessibility...\n";
$response = file_get_contents($api_url);
if ($response === false) {
    echo "❌ FAILED: Cannot access API at $api_url\n";
    echo "   Make sure your web server is running and the file is accessible.\n\n";
    exit(1);
} else {
    echo "✅ SUCCESS: API is accessible\n\n";
}

// Test 2: Test GET request (dashboard data)
echo "2. Testing GET request (dashboard data)...\n";
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/json'
    ]
]);

$response = file_get_contents($api_url, false, $context);
$data = json_decode($response, true);

if ($data === null) {
    echo "❌ FAILED: Invalid JSON response\n";
    echo "   Response: $response\n\n";
} else {
    echo "✅ SUCCESS: GET request working\n";
    echo "   Total slots: " . ($data['stats']['total_slots'] ?? 'N/A') . "\n";
    echo "   Available slots: " . ($data['stats']['available_slots'] ?? 'N/A') . "\n";
    echo "   Occupied slots: " . ($data['stats']['occupied_slots'] ?? 'N/A') . "\n\n";
}

// Test 3: Test POST request (parking status update)
echo "3. Testing POST request (parking status update)...\n";
$test_data = [
    'slot1' => 'occupied',
    'slot2' => 'empty',
    'slot3' => 'occupied',
    'entrance_gate' => 'open',
    'exit_gate' => 'closed'
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($test_data)
    ]
]);

$response = file_get_contents($api_url, false, $context);
$result = json_decode($response, true);

if ($result && isset($result['status']) && $result['status'] === 'success') {
    echo "✅ SUCCESS: POST request working\n";
    echo "   Message: " . ($result['message'] ?? 'N/A') . "\n\n";
} else {
    echo "❌ FAILED: POST request failed\n";
    echo "   Response: $response\n\n";
}

// Test 4: Test POST request (access log)
echo "4. Testing POST request (access log)...\n";
$test_log = [
    'card_uid' => 'TEST123456',
    'action' => 'entry',
    'status' => 'granted'
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($test_log)
    ]
]);

$response = file_get_contents($api_url, false, $context);
$result = json_decode($response, true);

if ($result && isset($result['status']) && $result['status'] === 'success') {
    echo "✅ SUCCESS: Access log POST working\n";
    echo "   Message: " . ($result['message'] ?? 'N/A') . "\n\n";
} else {
    echo "❌ FAILED: Access log POST failed\n";
    echo "   Response: $response\n\n";
}

// Test 5: Verify data persistence
echo "5. Testing data persistence...\n";
$response = file_get_contents($api_url, false, stream_context_create([
    'http' => ['method' => 'GET', 'header' => 'Content-Type: application/json']
]));
$data = json_decode($response, true);

if ($data && isset($data['slots'])) {
    $slot1_status = 'unknown';
    foreach ($data['slots'] as $slot) {
        if ($slot['slot_number'] == 1) {
            $slot1_status = $slot['status'];
            break;
        }
    }
    
    if ($slot1_status === 'occupied') {
        echo "✅ SUCCESS: Data persistence working\n";
        echo "   Slot 1 status correctly updated to: $slot1_status\n\n";
    } else {
        echo "❌ FAILED: Data persistence issue\n";
        echo "   Expected slot 1 to be 'occupied', got: $slot1_status\n\n";
    }
} else {
    echo "❌ FAILED: Cannot verify data persistence\n\n";
}

// Test 6: Test dashboard HTML
echo "6. Testing dashboard HTML...\n";
$dashboard_url = $base_url . "/dashboard.php";
$dashboard_response = file_get_contents($dashboard_url);

if ($dashboard_response === false) {
    echo "❌ FAILED: Cannot access dashboard at $dashboard_url\n";
    echo "   Make sure dashboard.php is in the same directory as parking_api.php\n\n";
} else {
    if (strpos($dashboard_response, 'JoJo Smart Parking System') !== false) {
        echo "✅ SUCCESS: Dashboard HTML is accessible\n";
        echo "   Dashboard title found in HTML\n\n";
    } else {
        echo "❌ FAILED: Dashboard HTML seems incorrect\n";
        echo "   Expected title 'JoJo Smart Parking System' not found\n\n";
    }
}

echo "=== Integration Test Complete ===\n";
echo "\nNext steps:\n";
echo "1. Run the database_setup.sql script to create required tables\n";
echo "2. Update the Arduino code with your actual WiFi credentials\n";
echo "3. Update the serverURL in Arduino code to match your server IP\n";
echo "4. Upload Arduino code to your ESP32\n";
echo "5. Test the complete system integration\n";
?>
