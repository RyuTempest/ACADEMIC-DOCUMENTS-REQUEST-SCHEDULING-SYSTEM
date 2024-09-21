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

        // Create event for AM slot if there is remaining capacity
        if ($am_remaining > 0 && !empty($row['start_time_am']) && !empty($row['end_time_am'])) {
            $events[] = [
                'id' => $row['id'],
                'title' => 'AM Slot: ' . $am_remaining . ' available',
                'start' => $row['slot_date'],
                'end' => $row['slot_date'],
                'slotId' => $row['id'],
                'slot_type' => 'AM',
                'capacity' => $row['am_capacity'],
                'booked' => $row['am_booked'],
                'remaining' => $am_remaining,
                'color' => 'red'
            ];
        }

        // Create event for PM slot if there is remaining capacity
        if ($pm_remaining > 0 && !empty($row['start_time_pm']) && !empty($row['end_time_pm'])) {
            $events[] = [
                'id' => $row['id'],
                'title' => 'PM Slot: ' . $pm_remaining . ' available',
                'start' => $row['slot_date'],
                'end' => $row['slot_date'],
                'slotId' => $row['id'],
                'slot_type' => 'PM',
                'capacity' => $row['pm_capacity'],
                'booked' => $row['pm_booked'],
                'remaining' => $pm_remaining,
                'color' => 'blue'
            ];
        }
    }
}

// Output the events as JSON
echo json_encode($events);

$conn->close();
?>
