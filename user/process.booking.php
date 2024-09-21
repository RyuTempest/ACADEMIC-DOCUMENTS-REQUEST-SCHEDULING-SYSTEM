<?php
include '../include/config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: /book/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$slot_id = $_POST['slot_id'];
$slot_type = $_POST['slot_type']; // AM or PM
$document_type = $_POST['document_type'];
$price = $_POST['price'];

// Validate input
if (empty($slot_id) || empty($slot_type) || empty($document_type) || empty($price)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit();
}

// Fetch the slot date from the database based on the slot ID
$sql = "SELECT slot_date, am_capacity, am_booked, pm_capacity, pm_booked FROM slots WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $slot_id);
$stmt->execute();
$stmt->bind_result($slot_date, $am_capacity, $am_booked, $pm_capacity, $pm_booked);
$stmt->fetch();
$stmt->close();

if (!$slot_date) {
    echo json_encode(['status' => 'error', 'message' => 'Slot not found']);
    exit();
}

// Check if the user already has a booking on the selected slot date
$sql = "SELECT COUNT(*) FROM bookings WHERE user_id = ? AND slot_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $slot_date);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You already have a booking on this date.']);
    exit();
}

// Start transaction
$conn->begin_transaction();

try {
    // Update booked count based on slot type
    if ($slot_type === 'AM') {
        if ($am_booked >= $am_capacity) {
            throw new Exception('No available AM slots.');
        }
        $am_booked++;
    } elseif ($slot_type === 'PM') {
        if ($pm_booked >= $pm_capacity) {
            throw new Exception('No available PM slots.');
        }
        $pm_booked++;
    } else {
        throw new Exception('Invalid slot type.');
    }

    // Update slot booking count
    $sql = "UPDATE slots SET am_booked = ?, pm_booked = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $am_booked, $pm_booked, $slot_id);
    $stmt->execute();
    $stmt->close();

      // Generate a unique reference number
      $reference_number = generateReferenceNumber($conn);

  // Insert booking record with reference number and slot date
  $sql = "INSERT INTO bookings (user_id, slot_id, slot_date, document_type, price, reference_number) 
  VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissss", $user_id, $slot_id, $slot_date, $document_type, $price, $reference_number);
$stmt->execute();
$stmt->close();


// Commit transaction
$conn->commit();

echo json_encode(['status' => 'success', 'message' => 'Booking successful', 'reference_number' => $reference_number]);
} catch (Exception $e) {
// Rollback transaction on error
$conn->rollback();
echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

// Close connection
$conn->close();

function generateReferenceNumber($conn) {
    $isUnique = false;
    $referenceNumber = '';

    while (!$isUnique) {
        $referenceNumber = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);

        $sql = "SELECT COUNT(*) FROM bookings WHERE reference_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $referenceNumber);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            $isUnique = true;
        }
    }
    return $referenceNumber;
}
?>
