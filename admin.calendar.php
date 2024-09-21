<?php

include '../include/config.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: /book/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission for adding slots
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slot_date = $_POST['slot_date'];
    $start_time_am = $_POST['start_time_am'];
    $end_time_am = $_POST['end_time_am'];
    $capacity_am = isset($_POST['capacity_am']) ? $_POST['capacity_am'] : 30;

    $start_time_pm = $_POST['start_time_pm'];
    $end_time_pm = $_POST['end_time_pm'];
    $capacity_pm = isset($_POST['capacity_pm']) ? $_POST['capacity_pm'] : 30;

    // Insert AM slot
    $sql = "INSERT INTO slots (user_id, slot_date, am_capacity, pm_capacity, am_booked, pm_booked) 
            VALUES (?, ?, ?, ?, 0, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $user_id, $slot_date, $capacity_am, $capacity_pm);
    $stmt->execute();
    $stmt->close();

    // Update the AM slot with start and end times
    $sql = "UPDATE slots SET start_time = ?, end_time = ? WHERE user_id = ? AND slot_date = ? AND am_capacity = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $start_time_am, $end_time_am, $user_id, $slot_date, $capacity_am);
    $stmt->execute();
    $stmt->close();

    // Update the PM slot with start and end times
    $sql = "UPDATE slots SET start_time = ?, end_time = ? WHERE user_id = ? AND slot_date = ? AND pm_capacity = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $start_time_pm, $end_time_pm, $user_id, $slot_date, $capacity_pm);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    // Redirect back to calendar page
    header("Location: /book/admin/calendar.php");
    exit;
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>Calendar</title>
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" href="assets/css/feathericon.min.css">
	<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="assets/plugins/fullcalendar/fullcalendar.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css">
	<link rel="stylesheet" href="assets/css/style.css"> </head>

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
										<div class="media">
                                            <span class="avatar avatar-sm">
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
										<div class="media">
                                            <span class="avatar avatar-sm">
                                                <img class="avatar-img rounded-circle" alt="User Image" src="assets/img/profiles/avatar-11.jpg">
                                            </span>
											<div class="media-body">
												<p class="noti-details"><span class="noti-title">International Software Inc</span> has sent you a invoice in the amount of <span class="noti-title">$218</span></p>
												<p class="noti-time"><span class="notification-time">6 mins ago</span> </p>
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
												<p class="noti-details"><span class="noti-title">John Hendry</span> sent a cancellation request <span class="noti-title">Apple iPhone XR</span></p>
												<p class="noti-time"><span class="notification-time">8 mins ago</span> </p>
											</div>
										</div>
									</a>
								</li>
								<li class="notification-message">
									<a href="#">
										<div class="media">
                                            <span class="avatar avatar-sm"><img class="avatar-img rounded-circle" alt="User Image" src="assets/img/profiles/avatar-13.jpg"></span>
											<div class="media-body">
												<p class="noti-details"><span class="noti-title">Mercury Software Inc</span> added a new product <span class="noti-title">Apple MacBook Pro</span></p>
												<p class="noti-time"><span class="notification-time">12 mins ago</span> </p>
											</div>
										</div>
									</a>
								</li>
							</ul>
						</div>
						<div class="topnav-dropdown-footer"> <a href="#">View all Notifications</a> </div>
					</div>
					<li class="nav-item dropdown has-arrow">
					<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"> <span class="user-img"><img class="rounded-circle" src="" width="31" alt=""></span> </a>
					<div class="dropdown-menu">
						<div class="user-header">
							<div class="avatar avatar-sm"> <img src="" alt="" class="avatar-img rounded-circle"> </div>
							<div class="user-text">
								<h6><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?>!</h6>
                                <p class="text-muted mb-0">Admin</p>
							</div>
						</div> <a class="dropdown-item" href="profile.html">My Profile</a> <a class="dropdown-item" href="login.php">Logout</a> </div>
				</li>
			</ul>
		</div>
		
											<!-----SIDEBAR----->
																<!------SIDEBAR----->
		<div class="sidebar" id="sidebar">
			<div class="sidebar-inner slimscroll">
				<div id="sidebar-menu" class="sidebar-menu">
					<ul>
						<li class="active"> <a href="admin.dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a> </li>
						<li class="list-divider"></li>
						<li class="submenu"> <a href="#"><i class="fas fa-suitcase"></i> <span> Appointments </span> <span class="menu-arrow"></span></a>
							<ul class="submenu_class" style="display: none;">
								<li><a href="all-booking.html"> Pending Appointment </a></li>
								<li><a href="edit-booking.html"> All Appoinment </a></li>
							</ul>
						</li>

                        <li class="active"> <a href="admin.calendar.php"><i class="fas fa-calendar-alt"></i> <span> Calendar </span></a></li>

						<li class="submenu"> <a href="#"><i class="fas fa-book"></i> <span> Documents</span> <span class="menu-arrow"></span></a>
							<ul class="submenu_class" style="display: none;">
								<li><a href="all-customer.html"> Pending </a></li>
                                <li><a href="all-customer.html"> Finished </a></li>
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

        
        <div class="page-wrapper">
			<div class="content container-fluid">
				<div class="page-header">
					<div class="row align-items-center">
						<div class="col">
							<div class="mt-5">
								<h4 class="card-title float-left mt-2">Calendar</h4>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-8">
					<div class="card">
						<div class="card-body">
							<div id="calendar"></div>
						</div>
					</div>
				</div>
<!-- Slot Form Modal -->
<div class="modal fade" id="slotModal" tabindex="-1" role="dialog" aria-labelledby="slotModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="slotModalLabel">Add Slot</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_slot_form" method="post" action="">
          <div class="form-group">
            <label for="slot_date">Slot Date:</label>
            <input type="text" name="slot_date" id="slot_date" class="form-control datetimepicker" required>
          </div>
          <div class="form-group">
            <label for="start_time_am">AM Start Time:</label>
            <input type="time" name="start_time_am" id="start_time_am" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="end_time_am">AM End Time:</label>
            <input type="time" name="end_time_am" id="end_time_am" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="capacity_am">AM Slot Capacity (default: 30):</label>
            <input type="number" name="capacity_am" id="capacity_am" class="form-control" value="30" min="1">
          </div>
          <div class="form-group">
            <label for="start_time_pm">PM Start Time:</label>
            <input type="time" name="start_time_pm" id="start_time_pm" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="end_time_pm">PM End Time:</label>
            <input type="time" name="end_time_pm" id="end_time_pm" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="capacity_pm">PM Slot Capacity (default: 30):</label>
            <input type="number" name="capacity_pm" id="capacity_pm" class="form-control" value="30" min="1">
          </div>
          <button type="submit" class="btn btn-primary">Add Slot</button>
        </form>
      </div>
    </div>
  </div>
</div>

	<script src="assets/js/jquery-3.5.1.min.js"></script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/js/moment.min.js"></script>
	<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
	<script src="assets/js/jquery-ui.min.js"></script>
	<script src="assets/plugins/fullcalendar/jquery.fullcalendar.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
	<script src="assets/js/script.js"></script>
	
	<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
        <?php unset($_SESSION['error']); ?>
    </div>
    <?php endif; // Combine slots and remaining_slots arrays
$all_slots = array_merge($slots, $remaining_slots);

// Pass combined array to JavaScript
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        selectable: true,
        dateClick: function(info) {
            var selectedDate = info.dateStr;

            // Check if the date has slots using AJAX
            fetch('admin.calendar.php?check_date=' + encodeURIComponent(selectedDate))
                .then(response => response.text())
                .then(data => {
                    if (data === '0') {
                        // If no slots, open the modal
                        document.getElementById('slot_date').value = selectedDate;
                        $('#slotModal').modal('show');
                    }
                    // Do nothing if slots exist; the date will be non-clickable
                });
        },
        events: <?php echo json_encode($all_slots); ?>, // Pass combined PHP array to JavaScript
        validRange: {
            start: 'today', // Adjust this if needed
            end: null
        },
        dayCellDidMount: function(info) {
            // Add custom logic to check if the date should be disabled
            fetch('admin.calendar.php?check_date=' + encodeURIComponent(info.dateStr))
                .then(response => response.text())
                .then(data => {
                    if (data === '1') {
                        // Disable the date if slots exist
                        info.el.style.pointerEvents = 'none';
                        info.el.style.opacity = '0.5';
                    }
                });
        }
    });

    calendar.render();
});
</script>
	<script>
		$(function () {
            $('#datetimepicker3').datetimepicker({
                format: 'YYYY-MM-DD '
            });
        });

	</script>
</body>

</html>