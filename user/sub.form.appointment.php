<?php
include '../include/config.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: /book/auth/login.php");
    exit();
}

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['user_id'];

// Initialize form variables
$firstname = $lastname = $email = $cellphone = $username = '';
$year_section = $year_graduated = $course_id = $birth_date = '';
$form_submitted = false;

// Check if the user has already submitted the form
$sql = "SELECT COUNT(*) FROM user_submissions WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($submission_count);
$stmt->fetch();
$stmt->close();

if ($submission_count > 0) {
    $form_submitted = true;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $year_section = $_POST['year_section'];
    $year_graduated = $_POST['year_graduated'];
    $course_id = $_POST['course_id'];
    $birth_date = $_POST['birth_date'];

    if (!$form_submitted) {
        // Insert data into user_submissions table
        $sql = "INSERT INTO user_submissions (user_id, year_section, year_graduated, course_id, birth_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $user_id, $year_section, $year_graduated, $course_id, $birth_date);
        $stmt->execute();
        $stmt->close();
        
        // Redirect to a confirmation page or show a success message
        header("Location: /book/user/calendar.php");
        exit();

    } else {
        // Update existing submission
        $sql = "UPDATE user_submissions SET year_section = ?, year_graduated = ?, course_id = ?, birth_date = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $year_section, $year_graduated, $course_id, $birth_date, $user_id);
        $stmt->execute();
        $stmt->close();
        
        // Redirect to the form update page
        header("Location: /book/user/calendar.php");
        exit();
    }
}

// Retrieve user details
$sql = "SELECT firstname, lastname, email, cellphone, username FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname, $email, $cellphone, $username);
$stmt->fetch();
$stmt->close();

?>







<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<title></title>

<link rel="shortcut icon" type="image/x-icon" href="/book/assets/img/favicon.png">

<link rel="stylesheet" href="/book/assets/css/bootstrap.min.css">

<link rel="stylesheet" href="/book/assets/plugins/fontawesome/css/all.min.css">
<link rel="stylesheet" href="/book/assets/plugins/fontawesome/css/fontawesome.min.css">

<link rel="stylesheet" href="/book/assets/css/feathericon.min.css">

<link rel="stylesheet" href="/book/assets/plugins/datatables/datatables.min.css">
<link rel="stylesheet" href="/book/assets/plugins/morris/morris.css">
<link rel="stylesheet" type="text/css" href="/book/assets/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" href="/book/assets/css/select2.min.css">

<link rel="stylesheet" href="/book/assets/css/style.css">

</head>
<body>

<div class="main-wrapper">
		<div class="header">
			<div class="header-left">
				<a href="/book/user/dashboard.php" class=""> <img src="" width="50" height="70" alt=""> <span class="">APP</span> </a>
			</div>
			<a href="javascript:void(0);" id="toggle_btn"> <i class="fe fe-text-align-left"></i> </a>
			<a class="mobile_btn" id="mobile_btn"> <i class="fas fa-bars"></i> </a>

                                        <!-----DASHBOARD----->
<ul class="nav user-menu">

<li class="nav-item dropdown noti-dropdown">
<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
<i class="fe fe-bell"></i> <span class="badge badge-pill">3</span>
</a>
<div class="dropdown-menu notifications">
<div class="topnav-dropdown-header">
<span class="notification-title">Notifications</span>
<a href="javascript:void(0)" class="clear-noti"> Clear All </a>
</div>
<div class="noti-content">
<ul class="notification-list">
<li class="notification-message">
<a href="#">
<div class="media">
<span class="avatar avatar-sm">
<img class="avatar-img rounded-circle" alt="User Image" src="assets/img/profiles/avatar-02.jpg">
</span>
<div class="media-body">
<p class="noti-details"><span class="noti-title">Carlson Tech</span> has
approved <span class="noti-title">your estimate</span></p>
<p class="noti-time"><span class="notification-time">4 mins ago</span>
</p>
</div>
</div>
</a>
</li>
<li class="notification-message">
<a href="#">
<div class="media">
<span class="avatar avatar-sm">
<img class="avatar-img rounded-circle" alt="User Image" src="assets/img/profiles/avatar-11.jpg">
</span>
<div class="media-body">
<p class="noti-details"><span class="noti-title">International Software
Inc</span> has sent you a invoice in the amount of <span class="noti-title">$218</span></p>
<p class="noti-time"><span class="notification-time">6 mins ago</span>
</p>
</div>
</div>
</a>
</li>
<li class="notification-message">
<a href="#">
<div class="media">
<span class="avatar avatar-sm">
<img class="avatar-img rounded-circle" alt="User Image" src="assets/img/profiles/avatar-17.jpg">
</span>
<div class="media-body">
<p class="noti-details"><span class="noti-title">John Hendry</span> sent
a cancellation request <span class="noti-title">Apple iPhone
XR</span></p>
<p class="noti-time"><span class="notification-time">8 mins ago</span>
</p>
</div>
</div>
</a>
</li>
<li class="notification-message">
<a href="#">
<div class="media">
<span class="avatar avatar-sm">
<img class="avatar-img rounded-circle" alt="User Image" src="assets/img/profiles/avatar-13.jpg">
</span>
<div class="media-body">
<p class="noti-details"><span class="noti-title">Mercury Software
Inc</span> added a new product <span class="noti-title">Apple
MacBook Pro</span></p>
<p class="noti-time"><span class="notification-time">12 mins ago</span>
</p>
</div>
</div>
</a>
</li>
</ul>
</div>
<div class="topnav-dropdown-footer">
<a href="#">View all Notifications</a>
</div>
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

                                        <!-----FORM LAYOUT----->
                                        <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header mt-5">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Personal Information</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Personal Information</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="" id="myForm">
                                <div class="row">
                                    <div class="col-xl-6">

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">First Name</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Last Name</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Email</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Cellphone Number</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="cellphone" value="<?php echo htmlspecialchars($cellphone); ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">TUPV ID Number</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="tupv_id" value="<?php echo htmlspecialchars($username); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Course</label>
                                            <div class="col-lg-9">
                                            <select class="select" name="course_id"  required>
                                                    <option>Select</option>
                                                    
                                                    <option value="1" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 1) ? 'selected' : ''; ?>>BS in Electronics Engineering</option>
                                                    <option value="2" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 2) ? 'selected' : ''; ?>>BS in Mechanical Engineering</option>
                                                    <option value="3" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 3) ? 'selected' : ''; ?>>BS in Electrical Engineering</option>
                                                    <option value="4" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 4) ? 'selected' : ''; ?>>BS in Computer Engineering</option>
                                                    <option value="5" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 5) ? 'selected' : ''; ?>>BS in Mechatronics Engineering</option>
                                                    <option value="6" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 6) ? 'selected' : ''; ?>>Bachelor of Science in Instrumentation & Control Engineering</option>
                                                    <option value="7" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 7) ? 'selected' : ''; ?>>BT in Mechatronics Technology</option>
                                                    <option value="8" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 8) ? 'selected' : ''; ?>>BS in Chemistry</option>
                                                    <option value="9" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 9) ? 'selected' : ''; ?>>BET in Automotive Engineering Technology</option>
                                                    <option value="10" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 10) ? 'selected' : ''; ?>>BET in HVAC-R Engineering Technology</option>
                                                    <option value="11" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 11) ? 'selected' : ''; ?>>BET in Computer Engineering Technology</option>
                                                    <option value="12" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 12) ? 'selected' : ''; ?>>BET in Manufacturing Engineering Technology</option>
                                                    <option value="13" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 13) ? 'selected' : ''; ?>>BET in Electronics Engineering Technology</option>
                                                    <option value="14" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 14) ? 'selected' : ''; ?>>BET in Electro-Mechanical Engineering Technology</option>
                                                    <option value="15" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 15) ? 'selected' : ''; ?>>BET in Electrical Engineering Technology</option>
                                                    <option value="16" <?php echo (isset($submission['course_id']) && $submission['course_id'] == 16) ? 'selected' : ''; ?>>BET in Chemical Engineering Technology</option>
    
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Year & Section</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="year_section" value="<?= htmlspecialchars($year_section) ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Year Graduated</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="year_graduated" value="<?= htmlspecialchars($year_graduated) ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Birth Date</label>
                                            <div class="cal-icon">
                                            <input type="text" class="form-control datetimepicker" name="birth_date"  value="<?= htmlspecialchars($birth_date) ?>">
                                        </div>
                                    </div>
                                </div>
                               </div>
                                    
                               <div class="text-right">
                                      <div class="text-right">
                                        <button type="submit" class="btn btn-primary" name="submit" >Submit</button>
                                        
                                    </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script data-cfasync="false" src="../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/js/jquery-3.5.1.min.js"></script>

<script src="/book/assets/js/jquery-3.5.1.min.js"></script>

<script src="/book/assets/js/popper.min.js"></script>
<script src="/book/assets/js/bootstrap.min.js"></script>
<script src="/book/assets/js/moment.min.js"></script>
<script src="/book/assets/js/select2.min.js"></script>

<script src="/book/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>\

<script src="/book/assets/js/bootstrap-datetimepicker.min.js"></script>

<script src="/book/assets/plugins/datatables/datatables.min.js"></script>
<script src="/book/assets/js/script.js"></script>

<script>
    $(function () {
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD'
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