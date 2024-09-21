<?php
// Include your database connection
include '../include/config.php';

// Query the database for AM/PM slots
$sql = "SELECT id, slot_date, start_time_am, end_time_am, am_capacity, am_booked, start_time_pm, end_time_pm, pm_capacity, pm_booked 
        FROM slots";
$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calculate remaining capacity for AM and PM
        $am_remaining = $row['am_capacity'] - $row['am_booked'];
        $pm_remaining = $row['pm_capacity'] - $row['pm_booked'];

        // Create event for AM slot
        $events[] = [
            'id' => $row['id'],
            'title' => 'AM Available: ' . max($am_remaining, 0), // Show 0 if negative
            'start' => $row['slot_date'] ,
            'end' => $row['slot_date'] ,
            'slotId' => $row['id'],
            'slot_type' => 'AM',
            'capacity' => $row['am_capacity'],
            'booked' => $row['am_booked'],
            'remaining' => max($am_remaining, 0), // Ensure remaining is not negative
            'color' => $am_remaining > 0 ? 'red' : 'gray' // Gray for fully booked
        ];

        // Create event for PM slot
        $events[] = [
            'id' => $row['id'],
            'title' => 'PM Available: ' . max($pm_remaining, 0), // Show 0 if negative
            'start' => $row['slot_date'] ,
            'end' => $row['slot_date'] ,
            'slotId' => $row['id'],
            'slot_type' => 'PM',
            'capacity' => $row['pm_capacity'],
            'booked' => $row['pm_booked'],
            'remaining' => max($pm_remaining, 0), // Ensure remaining is not negative
            'color' => $pm_remaining > 0 ? 'blue' : 'gray' // Gray for fully booked
        ];
    }
}

// Output the events as JSON
echo json_encode($events);

$conn->close();
?>
