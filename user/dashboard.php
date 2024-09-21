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
$stmt->bind_result($firstname, $lastname);
$stmt->fetch();
$stmt->close();

// Check if the user has submitted the form
$sql = "SELECT COUNT(*) FROM user_submissions WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($submission_count);
$stmt->fetch();
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets\img\tup-logo.png">
    <link rel="stylesheet" href="/book/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/book/assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/book/assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/book/assets/css/style.css">
</head>

<body>
    <div class="main-wrapper">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <a href="/book/user/dashboard.php" class="logo">
                    <img src="assets\img\tup-logo.png" width="50" height="70" alt="Logo">
                </a>
            </div>
            <a href="javascript:void(0);" id="toggle_btn"><i class="fe fe-text-align-left"></i></a>
            <a class="mobile_btn" id="mobile_btn"><i class="fas fa-bars"></i></a>
            <ul class="nav user-menu">
                <li class="nav-item dropdown noti-dropdown">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <i class="fas fa-bell"></i> <span class="badge badge-pill">3</span>
                    </a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notifications</span>
                            <a href="javascript:void(0)" class="clear-noti">Clear All</a>
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                <!-- Notification Items -->
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media">
                                            <span class="avatar avatar-sm">
                                                <img class="avatar-img rounded-circle" alt="User Image" src="assets\img\tup-logo.png">
                                            </span>
                                            <div class="media-body">
                                                <p class="noti-details"><span class="noti-title">Carlson Tech</span> has approved <span class="noti-title">your estimate</span></p>
                                                <p class="noti-time"><span class="notification-time">4 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- More Notification Items -->
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="#">View all Notifications</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <span class="user-img"><img class="rounded-circle" src="" width="31" alt=""></span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="" alt="" class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></h6>
                                <p class="text-muted mb-0">User</p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="profile.html">My Profile</a>
                        <a class="dropdown-item" href="/book/auth/login.php">Logout</a>
                    </div>
                </li>
            </ul>
            <div class="top-nav-search">
                <form>
                    <input type="text" class="form-control" placeholder="Search here">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </header>
        <!-- /Header -->

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="active">
                            <a href="/book/user/dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
                        </li>
                        <li class="list-divider"></li>
                        <li class="submenu">
                            <a href="#"><i class="fas fa-suitcase"></i> <span>Appointments</span> <span class="menu-arrow"></span></a>
                            <ul class="submenu_class" style="display: none;">
                                <li><a href="" id="make-appointment">Make an Appointment</a></li>
                                <li><a href="/book/user/pending.appointment.php">Pending Appointment</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="/book/user/history.php"><i class="fas fa-history"></i> <span>History</span></a>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="far fa-bell"></i> <span>Notifications</span> <span class="menu-arrow"></span></a>
                            <ul class="submenu_class" style="display: none;"></ul>
                        </li>
                        <li>
                            <a href="pricing.html"><i class="fas fa-cog"></i> <span>Settings</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        <!-- /Sidebar -->

        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12 mt-5">
                            <h3 class="page-title mt-3">Welcome <?php echo htmlspecialchars($firstname); ?></h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Content goes here -->
            </div>
        </div>
        <!-- /Page Wrapper -->
    </div>

    <script src="/book/assets/js/jquery-3.5.1.min.js"></script>
    <script src="/book/assets/js/popper.min.js"></script>
    <script src="/book/assets/js/bootstrap.min.js"></script>
    <script src="/book/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/book/assets/plugins/raphael/raphael.min.js"></script>
    <script src="/book/assets/plugins/morris/morris.min.js"></script>
    <script src="/book/assets/js/chart.morris.js"></script>
    <script src="/book/assets/js/script.js"></script>

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
