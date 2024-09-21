<?php
include 'config.php';

session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

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

<link rel="shortcut icon" type="image/x-icon" href="assets/img/tup-logo.png">

<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">

<link rel="stylesheet" href="assets/css/feathericon.min.css">

<link rel="stylesheet" href="assets/plugins/datatables/datatables.min.css">
<link rel="stylesheet" href="assets/plugins/morris/morris.css">
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" href="assets/css/select2.min.css">

<link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<div class="main-wrapper">
		<div class="header">
			<div class="header-left">
				<a href="user.dashboard.php" class=""> <img src="" width="50" height="70" alt=""> <span class="">APP</span> </a>
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
<h6><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?>!</h6>
<p class="text-muted mb-0">User</p>
</div>
</div> <a class="dropdown-item" href="profile.html">My Profile</a> <a class="dropdown-item" href="login.php">Logout</a> </div>
</li>

</ul>

</div>

                                        <!-----SIDEBAR----->
                                        <div class="sidebar" id="sidebar">
<div class="sidebar-inner slimscroll">
<div id="sidebar-menu" class="sidebar-menu">
	    <ul>
	<li class="active"> <a href="user.dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a> </li>
	<li class="list-divider"></li>
	<li class="submenu"> <a href="#"><i class="fas fa-suitcase"></i> <span> Appointments </span> <span class="menu-arrow"></span></a>
	    <ul class="submenu_class" style="display: none;">
	<li><a href="form.appointment.php"> Make an Appointment </a></li>
	<li><a href="edit-booking.html"> All Appoinment </a></li>
	    </ul>
    </li>

	<li class="submenu"> <a href="#"><i class="fas fa-book"></i> <span> Documents</span> <span class="menu-arrow"></span></a>
	    <ul class="submenu_class" style="display: none;">
	<li><a href="all-customer.html"> Pending </a></li>
	<li><a href="edit-customer.html"> Done </a></li>
		</ul>
	</li>

	<li class="submenu"> <a href="#"><i class="far fa-money-bill-alt"></i> <span> Payment </span> <span class="menu-arrow"></span></a>
		<ul class="submenu_class" style="display: none;">
	<li><a href="all-rooms.html">Pending </a></li>
	<li><a href="edit-room.html"> Paid </a></li>
		</ul>
	</li>

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

										<div class="main-wrapper">
    <!-- Include header and sidebar here -->
    <div class="page-wrapper">
        <div class="content container-fluid mt-5">
            <div class="card-body">
                <h4 class="card-title">Document Booking</h4>
                <form method="POST" action="" id="myForm">
                    <div class="row">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Document Type</label>
                                <div class="col-lg-9">
                                    <select class="form-control" id="document_type" name="document_type" onchange="updatePrice()">
                                        <option value="">Select Document</option>
                                        <option value="TOR">TOR</option>
                                        <option value="Diploma">Diploma</option>
                                        <!-- Add more options if needed -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Price</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="price" name="price" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





<script data-cfasync="false" src="../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/js/jquery-3.5.1.min.js"></script>

<script src="assets/js/jquery-3.5.1.min.js"></script>

<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/moment.min.js"></script>
<script src="assets/js/select2.min.js"></script>

<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>\

<script src="assets/js/bootstrap-datetimepicker.min.js"></script>

<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>
<script>
function updatePrice() {
    var documentType = document.getElementById('document_type').value;
    var price = 0;

    switch(documentType) {
        case 'TOR':
            price = 100;
            break;
        case 'Diploma':
            price = 200;
            break;
        default:
            price = 0;
    }

    document.getElementById('price').value = price;
}
</script>
</body>
</html>