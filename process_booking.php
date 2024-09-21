<?php
// Include the database configuration file
include '../include/config.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

// Get the form data
$user_id = $_SESSION['user_id'];
$slot_id = $_POST['slot_id'];
$document_type = $_POST['document_type'];
$price = $_POST['price'];
$slot_date = $_POST['slot_date'];
$slot_time = $_POST['slot_time']; // e.g., 'AM' or 'PM'

// Validate the inputs
if (empty($slot_id) || empty($document_type) || empty($price)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid form data']);
    exit();
}

// Start a transaction to ensure data integrity
$conn->begin_transaction();

try {
    // Insert the booking data into the booking table
    $sql = "INSERT INTO bookings (user_id, slot_id, document_type, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Database error: Failed to prepare statement.');
    }

    $stmt->bind_param("iisd", $user_id, $slot_id, $document_type, $price);
    
    if (!$stmt->execute()) {
        throw new Exception('Database error: Failed to execute booking insert.');
    }
    $stmt->close();

    // Update the slots table
    if ($slot_time === 'AM') {
        $update_sql = "UPDATE slots SET am_booked = am_booked + 1 WHERE id = ? AND slot_date = ?";
    } else {
        $update_sql = "UPDATE slots SET pm_booked = pm_booked + 1 WHERE id = ? AND slot_date = ?";
    }

    $update_stmt = $conn->prepare($update_sql);
    
    if (!$update_stmt) {
        throw new Exception('Database error: Failed to prepare update statement.');
    }

    $update_stmt->bind_param("is", $slot_id, $slot_date);
    
    if (!$update_stmt->execute()) {
        throw new Exception('Database error: Failed to update booked slots.');
    }
    $update_stmt->close();

    // Commit the transaction
    $conn->commit();

    echo json_encode(['status' => 'success', 'message' => 'Booking successful']);

} catch (Exception $e) {
    // Rollback the transaction in case of any error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>
