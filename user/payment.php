<?php
include '../include/config.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: /book/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT firstname, lastname FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname);
$stmt->fetch();
$stmt->close();

// Fetch booking details based on user_id
$sql = "SELECT id, reference_number, document_type, price, slot_date, slot_type, slot_id FROM bookings WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($booking_id, $reference_number, $document_type, $price, $slot_date, $slot_type, $slot_id);
$stmt->fetch();
$stmt->close();

// Fetch slot times
$start_time = $end_time = '';
$sql = "SELECT start_time_am, end_time_am, start_time_pm, end_time_pm FROM slots WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $slot_id);
$stmt->execute();
$stmt->bind_result($start_time_am, $end_time_am, $start_time_pm, $end_time_pm);
$stmt->fetch();
$stmt->close();

if ($slot_type === 'AM') {
    $start_time = $start_time_am;
    $end_time = $end_time_am;
} elseif ($slot_type === 'PM') {
    $start_time = $start_time_pm;
    $end_time = $end_time_pm;
}

// Retrieve additional details from user_submissions
$sql_submissions = "SELECT course_id FROM user_submissions WHERE user_id = ?";
$stmt_submissions = $conn->prepare($sql_submissions);
$stmt_submissions->bind_param("i", $user_id);
$stmt_submissions->execute();
$stmt_submissions->bind_result($course_id);
$stmt_submissions->fetch();
$stmt_submissions->close();

// Define the course mapping
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

// Get the course name
$course_name = $course_mapping[$course_id] ?? 'Unknown Course';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize POST data
    $payment_method = "Over the Counter";
    $document_type = $_POST['document_type'];
	$application = "Appearance";
	$status = "pending";
	 
    
    // Prepare and execute SQL statement
    $sql = "INSERT INTO transactions (user_id, booking_id, payment_method, status, price, document_type, application) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("iissssi", $user_id, $booking_id, $payment_method, $status, $price, $document_type, $application);

    if ($stmt->execute()) {
        header("Location: /book/user/pending.appointment.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $conn->close();
}

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
	<link rel="stylesheet" href="/book/assets/css/feathericon.min.css">
	<link rel="stylesheet" href="/book/assets/plugins/morris/morris.css">
	<link rel="stylesheet" type="text/css" href="/book/assets/css/bootstrap-datetimepicker.min.css">
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
								<p class="text-muted mb-0">User</p>
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
								<li><a href="/book/user/pending.appointment.php"> All Appoinment </a></li>
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
				<div class="page-header mt-5">
				</div>
				
						<form action="" method="POST">
						<div class="tab-content profile-tab-cont">
							<div class="tab-pane fade show active" id="per_details_tab">
								<div class="row">
									<div class="col-lg-8" style="padding-left:220px; padding-top:0px;	">
										<div class="card" style="width: 200%; max-width: 800px; padding: 0px; height: 100%; background-color: #E8EAED; text-align:center;">
												
												<h5 class="header-row" style="background-color:#C8DFEA; padding-top:10px; text-align:center; height:70px; font-size:25px;">
													<div class="header-row">
													<span ><b>PAYMENT</b></span>
													</div>
													</h5>

													<div class="col-md-10">
									
													<div class="row mt-5">
                                        <p class="col-sm-3 label-col" style="text-align: left; padding-left:100px; white-space:nowrap; font-size:19px;">Name <span style="margin-left: 134px;">:</span></p>
                                        <p class="col-sm-9 value-col" style="text-align:left; padding-left:145px; align-items:20px; font-size:19px;"><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
										<input type="hidden" name="name" value="<?php echo htmlspecialchars($firstname . ' ' . $lastname); ?>" readonly>
                                    </div>
									<div class="row">
                                        <p class="col-sm-3 label-col"  style="text-align: left; padding-left:100px; white-space:nowrap; font-size:19px;">Course <span style="margin-left: 126px;">:</span></p>
                                        <p class="col-sm-9 value-col"  style="text-align:left; padding-left:145px; align-items:20px; font-size:19px;"><?php echo htmlspecialchars($course_name); ?> </p>
										<input type="hidden" name="course" value="<?php echo htmlspecialchars($course_name); ?>" readonly>
                                    </div>
                                    <div class="row">
                                        <p class="col-sm-3 label-col"  style="text-align: left; padding-left:100px; white-space:nowrap; font-size:19px;">Reference # <span style="margin-left: 85px;">:</span></p>
                                        <p class="col-sm-9 value-col"  style="text-align:left; padding-left:145px; align-items:20px; font-size:19px;"><?php echo htmlspecialchars($reference_number); ?></p>
										<input type="hidden" name="reference_number" value="<?php echo htmlspecialchars($reference_number); ?>" readonly>
                                    </div>
                                    <div class="row">
                                        <p class="col-sm-3 label-col"  style="text-align: left; padding-left:100px; white-space:nowrap; font-size:19px;">Document Type	<span style="margin-left: 52px;">:</span></p>
                                        <p class="col-sm-9 value-col"  style="text-align:left; padding-left:145px; align-items:20px; font-size:19px;"><?php echo htmlspecialchars($document_type); ?></p>
										<input type="hidden" name="document_type" value="<?php echo htmlspecialchars($document_type); ?>" readonly>
                                    </div>
									<div class="row">
    									<p class="col-sm-3 label-col" style="text-align: left; padding-left:100px; white-space:nowrap; font-size:19px;">Amount <span style="margin-left: 116px;">:</span></p>
    									<p class="col-sm-9 value-col" style="text-align:left; padding-left:145px; align-items:20px; font-size:19px;"><?php echo htmlspecialchars($price); ?></p>
    									<!-- Hidden input to pass the price in the form -->
    									<input type="hidden" name="price" value="<?php echo htmlspecialchars($price); ?>" readonly>
									</div>
                                    <div class="row">
                                        <p class="col-sm-3 label-col"  style="text-align: left; padding-left:100px; white-space:nowrap; font-size:19px;">Appointment Date <span style="margin-left:28px;">:</span></p>
                                        <p class="col-sm-9 value-col"  style="text-align:left; padding-left:145px; align-items:20px; font-size:19px;"><?php echo htmlspecialchars($slot_date . ' /' .$start_time. '-' .$end_time. ' ' . $slot_type); ?></p>
										<input type="hidden" name="appointment_date" value="<?php echo htmlspecialchars($slot_date . ' /' .$start_time. '-' .$end_time. ' ' . $slot_type); ?>" readonly>
                                    </div>
									<!-- Hidden input to pass the booking ID -->
									<input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
                                    <div class="row" >
                                        <p class="col-sm-3 label-col"  style="text-align: left; padding-left:100px; white-space:nowrap; font-size:19px;">Application <span style="margin-left: 88px;">:</span></p>
                                        <p class="col-sm-9 value-col" style="text-align:left; padding-left:145px; align-items:20px; font-size:19px;" name="application">Appearance</p>
										<input type="hidden" name="application" value="Appearance">
                                    
                                    </div>
                                    <div class="row" >
                                        <p class="col-sm-3 label-col"  style="text-align: left; padding-left:100px; white-space:nowrap; font-size:19px;">Payment Method <span style="margin-left: 38px;">:</span></p>
                                        <p class="col-sm-9 value-col" style="text-align:left; padding-left:145px; align-items:20px; font-size:19px;" name="payment_method">Over the Counter</p>
										<input type="hidden" name="payment_method" value="Over the counter">
                                    
                                    </div>
										</div>
										<div class="d-inline-block me-2" style="padding-top:0px; padding-left:700px; ">
        									<button type="submit" class="btn btn-primary" name="submit">Print</button>
   										 </div>
									</div>
								</div>
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script data-cfasync="false" src="../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
	<script src="/book/assets/js/jquery-3.5.1.min.js"></script>
	<script src="/book/assets/js/popper.min.js"></script>
	<script src="/book/assets/js/bootstrap.min.js"></script>
	<script src="/book/assets/js/moment.min.js"></script>
	<script src="/book/assets/js/select2.min.js"></script>
	<script src="/book/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="/book/assets/js/bootstrap-datetimepicker.min.js"></script>
	<script src="/book/assets/js/script.js"></script>

	
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var checkbox = document.getElementById('firstTimeJobSeekerCheckbox');
			var paymentSelect = document.getElementById('paymentSelect');

			function togglePaymentSelect() {
				if (checkbox.checked) {
					paymentSelect.classList.add('disabled-select');
					paymentSelect.disabled = true;
				} else {
					paymentSelect.classList.remove('disabled-select');
					paymentSelect.disabled = false;
				}
			}

			// Initial call to set the state
			togglePaymentSelect();

			// Listen for changes to the checkbox
			checkbox.addEventListener('change', togglePaymentSelect);
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


	
