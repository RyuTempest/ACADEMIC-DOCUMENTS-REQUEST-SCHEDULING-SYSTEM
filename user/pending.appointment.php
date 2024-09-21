<?php
session_start();
include '../include/config.php';

// Redirect if not logged in or not a user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: /book/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT firstname, lastname FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname,$lastname);
$stmt->fetch();

$stmt->close();

// Fetch user details (firstname, lastname)
function getUserDetails($conn, $user_id) {
    $sql = "SELECT firstname, lastname FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($firstname, $lastname);
    $stmt->fetch();
    $stmt->close();
    return ['firstname' => $firstname, 'lastname' => $lastname];
}

$user = getUserDetails($conn, $user_id);

// Define course mapping
$course_mapping = [
    1 => "BS in Electronics Engineering",
    2 => "BS in Mechanical Engineering",
    3 => "BS in Electrical Engineering",
    4 => "BS in Computer Engineering",
    5 => "BS in Mechatronics Engineering",
    6 => "Bachelor of Science in Instrumentation & Control Engineering",
    7 => "BT in Mechatronics Technology",
    8 => "BS in Chemistry",
    9 => "BET in Automotive Engineering Technology",
    10 => "BET in HVAC-R Engineering Technology",
    11 => "BET in Computer Engineering Technology",
    12 => "BET in Manufacturing Engineering Technology",
    13 => "BET in Electronics Engineering Technology",
    14 => "BET in Electro-Mechanical Engineering Technology",
    15 => "BET in Electrical Engineering Technology",
    16 => "BET in Chemical Engineering Technology"
];

// Fetch transactions for the logged-in user
function getUserTransactions($conn, $user_id, $course_mapping) {
    $sql = "
       SELECT 
    t.id AS transaction_id,
    b.reference_number,
    us.course_id,
    t.document_type,
    t.price,
    b.slot_type,
    s.slot_date,
    s.start_time_am,
    s.end_time_am,
    s.start_time_pm,
    s.end_time_pm,
    t.application,
    t.payment_method,
    t.status,
    u.firstname,
    u.lastname
FROM transactions t
INNER JOIN bookings b ON t.booking_id = b.id
INNER JOIN slots s ON b.slot_id = s.id
INNER JOIN user_submissions us ON us.user_id = t.user_id
INNER JOIN user u ON u.id = t.user_id
WHERE t.user_id = ? AND t.status = 'Pending'
ORDER BY t.id DESC

    ";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("MySQLi prepare() error: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        die("MySQLi execute() error: " . htmlspecialchars($stmt->error));
    }

    $result = $stmt->get_result();
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $course_id = $row['course_id'];
        $row['course_name'] = $course_mapping[$course_id] ?? 'Unknown Course';
        
        // Set start and end times based on slot_type (AM/PM)
        if ($row['slot_type'] === 'AM') {
            $row['start_time'] = $row['start_time_am'];
            $row['end_time'] = $row['end_time_am'];
        } else {
            $row['start_time'] = $row['start_time_pm'];
            $row['end_time'] = $row['end_time_pm'];
        }

        $transactions[] = $row;
    }

    $stmt->close();
    return $transactions;
}

$transactions = getUserTransactions($conn, $user_id, $course_mapping);

$conn->close();
?>






<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>Hotel Dashboard Template</title>
	<link rel="shortcut icon" type="image/x-icon" href="/book/assets/img/favicon.png">
	<link rel="stylesheet" href="/book/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="/book/assets/plugins/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="/book/assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" href="/book/assets/plugins/datatables/datatables.min.css">
	<link rel="stylesheet" href="/book/assets/css/feathericon.min.css">
	<link rel="stylesheet" href="/book/assets/plugins/morris/morris.css">
	<link rel="stylesheet" href="/book/assets/css/style.css"> </head>

<body>
	<div class="main-wrapper">
		<div class="header">
			<div class="header-left">
				<a href="index.html" class="logo"> <img src="assets/img/hotel_logo.png" width="50" height="70" alt="logo"> <span class="logoclass">HOTEL</span> </a>
				<a href="index.html" class="logo logo-small"> <img src="assets/img/hotel_logo.png" alt="Logo" width="30" height="30"> </a>
			</div>
			<a href="javascript:void(0);" id="toggle_btn"> <i class="fe fe-text-align-left"></i> </a>
			<a class="mobile_btn" id="mobile_btn"> <i class="fas fa-bars"></i> </a>
			<ul class="nav user-menu">
				<li class="nav-item dropdown noti-dropdown">
					<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"> <i class="fe fe-bell"></i> <span class="badge badge-pill">3</span> </a>
					<div class="dropdown-menu notifications">
						<div class="topnav-dropdown-header"> <span class="notification-title">Notifications</span> <a href="javascript:void(0)" class="clear-noti"> Clear All </a> </div>
						<div class="noti-content">
							<ul class="notification-list">
								<li class="notification-message">
									<a href="#">
										<div class="media"> <span class="avatar avatar-sm">
                                            <img class="avatar-img rounded-circle" alt="User Image" src="assets/img/profiles/avatar-02.jpg">
                                            </span>
											<div class="media-body">
												<p class="noti-details"><span class="noti-title">Carlson Tech</span> has approved <span class="noti-title">your estimate</span></p>
												<p class="noti-time"><span class="notification-time">4 mins ago</span> </p>
											</div>
										</div>
									</a>
								</li>
								<li class="notification-message">
									<a href="#">
										<div class="media"> <span class="avatar avatar-sm">
                                            <img class="avatar-img rounded-circle" alt="User Image" src="assets/img/profiles/avatar-11.jpg">
                                            </span>
											<div class="media-body">
												<p class="noti-details"><span class="noti-title">International Software
                                                    Inc</span> has sent you a invoice in the amount of <span class="noti-title">$218</span></p>
												<p class="noti-time"><span class="notification-time">6 mins ago</span> </p>
											</div>
										</div>
									</a>
								</li>
								<li class="notification-message">
									<a href="#">
										<div class="media"> <span class="avatar avatar-sm">
                                            <img class="avatar-img rounded-circle" alt="User Image" src="assets/img/profiles/avatar-17.jpg">
                                            </span>
											<div class="media-body">
												<p class="noti-details"><span class="noti-title">John Hendry</span> sent a cancellation request <span class="noti-title">Apple iPhone
                                                    XR</span></p>
												<p class="noti-time"><span class="notification-time">8 mins ago</span> </p>
											</div>
										</div>
									</a>
								</li>
								<li class="notification-message">
									<a href="#">
										<div class="media"> <span class="avatar avatar-sm">
                                            <img class="avatar-img rounded-circle" alt="User Image" src="assets/img/profiles/avatar-13.jpg">
                                            </span>
											<div class="media-body">
												<p class="noti-details"><span class="noti-title">Mercury Software
                                                    Inc</span> added a new product <span class="noti-title">Apple
                                                    MacBook Pro</span></p>
												<p class="noti-time"><span class="notification-time">12 mins ago</span> </p>
											</div>
										</div>
									</a>
								</li>
							</ul>
						</div>
						<div class="topnav-dropdown-footer"> <a href="#">View all Notifications</a> </div>
					</div>
				</li>
				<li class="nav-item dropdown has-arrow">
					<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"> <span class="user-img"><img class="rounded-circle" src="" width="31" alt=""></span> </a>
					<div class="dropdown-menu">
						<div class="user-header">
							<div class="avatar avatar-sm"> <img src="" alt="" class="avatar-img rounded-circle"> </div>
							<div class="user-text">
								<h6><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></h6>
								<p class="text-muted mb-0">Userr</p>
							</div>
						</div> <a class="dropdown-item" href="profile.html">My Profile</a> <a class="dropdown-item" href="/book/auth/login.php">Logout</a> </div>
				</li>
			</ul>
		</div>
		
																	<!-----SIDEBAR----->
		<div class="sidebar" id="sidebar">
			<div class="sidebar-inner slimscroll">
				<div id="sidebar-menu" class="sidebar-menu">
					<ul>
						<li class="active"> <a href="/book/user/dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a> </li>
						<li class="list-divider"></li>
						<li class="submenu"> <a href="#"><i class="fas fa-suitcase"></i> <span> Appointments </span> <span class="menu-arrow"></span></a>
							<ul class="submenu_class" style="display: none;">
								<li><a href="" id="make-appointment" > Make an Appointment </a></li>
								<li><a href="/book/user/pending.appointment.php"> Pending Appoinment </a></li>
							</ul>
						</li>
                        <li> <a href="/book/user/history.php"><i class="fe fe-table"></i> <span>History</span></a> </li>

						<li class="submenu"> <a href="#"><i class="far fa-bell"></i> <span> Notifications </span> <span class="menu-arrow"></span></a>
							<ul class="submenu_class" style="display: none;">
							</ul>
						</li>
						<li> <a href="pricing.html"><i class="fas fa-cog"></i> <span>Settings</span></a> </li>
					
					</ul>
				</div>
			</div>
		</div>
		
		<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title float-left mt-2">Transaction</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table">
                            <div class="card-body booking_card">
                                <div class="table-responsive">
                                    <table class="datatable table table-striped table-hover table-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Reference Number</th>
                                                <th>Name</th>
                                                <th>Course</th>
                                                <th>Document Type</th>
                                                <th>Price</th>
                                                <th>Appointment Date</th>
                                                <th>Application</th>
                                                <th>Payment Method</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($transactions) > 0): ?>
                                                <?php foreach ($transactions as $transaction): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($transaction['reference_number']); ?></td>
														<td><?php echo htmlspecialchars($transaction['firstname'] . '  ' . $transaction['lastname']); ?></td>
                                                        <td><?php echo htmlspecialchars($transaction['course_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($transaction['document_type']); ?></td>
                                                        <td><?php echo htmlspecialchars($transaction['price']); ?></td>
                                                        <td>
                                                            <?php echo htmlspecialchars($transaction['slot_date']) . '<br>' .
                                                                  htmlspecialchars($transaction['start_time']) . ' - ' .
                                                                  htmlspecialchars($transaction['end_time']) . '<br>' .
                                                                  htmlspecialchars($transaction['slot_type']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($transaction['application']); ?></td>
                                                        <td><?php echo htmlspecialchars($transaction['payment_method']); ?></td>
                                                        <td style="color:green;"><?php echo htmlspecialchars($transaction['status']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="11" class="text-center">No transactions found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/book/assets/js/jquery-3.5.1.min.js"></script>
    <script src="/book/assets/js/popper.min.js"></script>
    <script src="/book/assets/js/bootstrap.min.js"></script>
    <script src="/book/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/book/assets/plugins/datatables/datatables.min.js"></script>
    <script src="/book/assets/js/script.js"></script>
    <script>
  $(document).ready(function() {
    var table = $('.datatable').DataTable();
    if (table) {
        table.destroy();
    }
    $('.datatable').DataTable({
        "order": [[0, "desc"]]  // Sort by Transaction ID descending
    });
});

</script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            var userHasSubmittedForm = <?php echo json_encode($submission_count > 0); ?>;
            var appointmentLink = document.getElementById('make-appointment');

            appointmentLink.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default link behavior

                if (userHasSubmittedForm) {
                    window.location.href = '/book/user/up.form.appointment.php'; // Redirect if form submitted
                } else {
                    window.location.href = '/book/user/sub.form.appointment.php'; // Redirect if form not submitted
                }
            });
        });
    </script>

</body>
</html>