<?php
include '../include/config.php';


session_start();
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


?>





<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>Hotel - Calendar</title>
	<link rel="shortcut icon" type="image/x-icon" href="/book/assets/img/favicon.png">
	<link rel="stylesheet" href="/book/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="/book/assets/plugins/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="/book/assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" href="/book/assets/css/feathericon.min.css">
	<link rel="stylesheet" href="/book/assets/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="/book/assets/plugins/fullcalendar/fullcalendar.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css">
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
				
				<!-- Modal for Document Form -->
<!-- Remove modal HTML for document form -->

<!-- Add Document Form Below Calendar -->
<div class="col-lg-12 col-md-8 mt-4">
    <div class="card">
        <div class="card-body">
		<form id="documentForm" style="display: none;">
    <div class="form-group">
        <label for="slot_date">Selected Date</label>
        <input type="text" class="form-control" id="slot_date" name="slot_date" readonly>
    </div>

    <input type="hidden" id="slot_id" name="slot_id"> <!-- Hidden input for slot_id -->
   
	<div class="form-group">
    <label for="slot_time">Selected Slot</label>
    <input type="text" class="form-control" id="slot_time" name="slot_time" readonly>
    <input type="hidden" id="slot_type" name="slot_type">
</div>

    <div class="form-group">
        <label for="document_type">Document Type</label>
        <select class="form-control" id="document_type" name="document_type">
            <option value="">Select Document</option>
            <option value="TOR">TOR</option>
            <option value="Diploma">Diploma</option>
        </select>
    </div>
    <div class="form-group">
        <label for="price">Price</label>
        <input type="text" class="form-control" id="price" name="price" readonly>
    </div>
    <button type="submit" class="btn btn-primary">Book Slot</button>
</form>

        </div>
    </div>
</div>
		
	<script src="/book/assets/js/jquery-3.5.1.min.js"></script>
	<script src="/book/assets/js/popper.min.js"></script>
	<script src="/book/assets/js/bootstrap.min.js"></script>
	<script src="/book/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="/book/assets/js/moment.min.js"></script>
	<script src="/book/assets/js/bootstrap-datetimepicker.min.js"></script>
	<script src="/book/assets/js/jquery-ui.min.js"></script>
	<script src="/book/assets/plugins/fullcalendar/jquery.fullcalendar.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
	<script src="/book/assets/js/script.js"></script>
   
   
    <script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var userBookings = []; // This will hold the user's bookings
    var bookedDates = {}; // This will hold dates where the user has booked

    // Fetch user's existing bookings from the server
function fetchUserBookings() {
    $.ajax({
        url: '/book/user/fetch.events.php', // Server endpoint to fetch user's bookings
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Create a lookup for easy access to booked dates
            bookedDates = data.reduce(function(acc, booking) {
                acc[booking.slot_date] = true;
                return acc;
            }, {});
        },
        error: function() {
            alert('An error occurred while fetching user bookings.');
        }
    });
}


    fetchUserBookings(); // Call to populate the bookings on page load

    var calendar = new FullCalendar.Calendar(calendarEl, {
        selectable: true,
        select: function(info) {
            var selectedDate = moment(info.startStr).format('YYYY-MM-DD');

            if (userBookings[selectedDate] || !bookedDates[selectedDate]) {
    
    calendar.unselect(); // Deselect the date
    $('#documentForm').hide(); // Hide the form
    return;
}

// Proceed to show the form if the date is available
document.getElementById('slot_date').value = selectedDate;
$('#documentForm').show(); // Show the document form
$('html, body').animate({
    scrollTop: $('#documentForm').offset().top
}, 800);

        },

        events: function(fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: '/book/user/fetch.events.php',
                dataType: 'json',
                success: function(data) {
                    successCallback(data);
                },
                error: function() {
                    failureCallback();
                }
            });
        },
        eventClick: function(info) {
            var eventDate = moment(info.event.startStr).format('YYYY-MM-DD');
            var slotType = info.event.extendedProps.slot_type;
            var slotCapacity = info.event.extendedProps.capacity;
            var remainingCapacity = info.event.extendedProps.remaining; // Assuming remaining capacity is included in event data

            $('#documentForm').hide(); // Hide the form initially

            if (userBookings[eventDate]) {
                alert('You already have a booking on this date.');
                info.jsEvent.preventDefault();  // Prevents default action if needed
                return;  // Exit the function to prevent further action
            }

            // Prevent interaction if remaining capacity is zero
            if (remainingCapacity <= 0) {
                alert('This slot is fully booked and cannot be selected.');
                info.jsEvent.preventDefault();  // Prevents default action if needed
                return;
            }

            // Proceed with showing the form if the slot is available
            document.getElementById('slot_date').value = eventDate;
            document.getElementById('slot_id').value = info.event.extendedProps.slotId;
            document.getElementById('slot_time').value = slotType;
            document.getElementById('slot_type').value = slotType;

            $('#calendar').slideUp();
            $('#documentForm').show();
            $('html, body').animate({
                scrollTop: $('#documentForm').offset().top
            }, 800);
        }
    });

    calendar.render();



    // Update price based on document type
    function updatePrice() {
    var documentType = document.getElementById('document_type').value;
    console.log('Selected Document Type:', documentType); // Add this line
    var price = 0;
    switch (documentType) {
        case 'TOR':
            price = 100;
            break;
        case 'Diploma':
            price = 200;
            break;
        default:
            price = 0;
    }
    document.getElementById('price').value = '₱' + price;
}


    document.getElementById('document_type').addEventListener('change', updatePrice);

    document.getElementById('documentForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    var formData = new FormData(document.getElementById('documentForm'));

    $.ajax({
        url: '/book/user/process.booking.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            var result = JSON.parse(response);
            if (result.status === 'success') {
                alert('Booking successful');
                window.location.href = '/book/user/payment.php'; // Redirect to payment page on success
            } else {
                alert('Error: ' + result.message);
                window.location.href = '/book/user/calendar.php'; // Redirect to calendar page on error
            }
        },
        error: function(xhr, status, error) {
                alert('An error occurred while processing the booking.');
            }
    });
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
