<?php
header('Content-Type: application/json');
include 'config.php';

// Fetch all events
$sql = "SELECT id, slot_date, start_time, end_time, capacity, booked FROM slots";
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['id'], // Add the slot ID here
        'title' => "Available: " . ($row['capacity'] - $row['booked']) . "/" . $row['capacity'],
        'start' => $row['slot_date'] . 'T' . $row['start_time'],
        'end' => $row['slot_date'] . 'T' . $row['end_time'],
        'capacity' => $row['capacity'],
        'booked' => $row['booked'] // Include booked for potential use
    ];
}

echo json_encode($events);
$conn->close();
?>
