<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

$admin_id = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slot_date = $_POST['slot_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $capacity = isset($_POST['capacity']) ? $_POST['capacity'] : 30;  // Default to 30 if not provided

    // Insert new slot into database
    $sql = "INSERT INTO slots (user_id, slot_date, start_time, end_time, capacity) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $admin_id, $slot_date, $start_time, $end_time, $capacity);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Slot added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add slot.']);
    }
    $stmt->close();
    $conn->close();

    // Redirect back to calendar page
    header("Location: admin.calendar.php");
    exit;
}
?>
