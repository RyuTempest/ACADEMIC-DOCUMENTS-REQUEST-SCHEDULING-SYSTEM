<?php
include '../include/config.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['user_id'];

// Retrieve existing user details
$sql = "SELECT firstname, middlename, lastname, suffix, email, cellphone, username FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $middlename, $lastname, $suffix, $email, $cellphone, $username);
$stmt->fetch();
$stmt->close();

// Retrieve additional details from user_submissions
$sql_submissions = "SELECT course_id, birth_date FROM user_submissions WHERE user_id = ?";
$stmt_submissions = $conn->prepare($sql_submissions);
$stmt_submissions->bind_param("i", $user_id);
$stmt_submissions->execute();
$stmt_submissions->bind_result($course_id, $birth_date);
$stmt_submissions->fetch();
$stmt_submissions->close();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Redirect to sub.form.php
    header("Location: /user/sub.form.appointment.php");
    exit();
}

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

// Get the course name based on course_id
$course_name = isset($course_mapping[$course_id]) ? $course_mapping[$course_id] : 'Unknown Course';


// Initialize errors array
$errors = [];

// Password change logic
if (isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the old password is correct
    $stmt = $conn->prepare("SELECT password FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($current_password);
    $stmt->fetch();
    $stmt->close();

     // Validate the old password and new password matching
if (!$current_password || !password_verify($old_password, $current_password)) {
    $errors[] = "Invalid old password!";
} elseif ($new_password !== $confirm_password) {
    $errors[] = "Passwords do not match!";
} else {
    // Hash the new password and store it in a variable
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update the user's password
    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);
    
    if ($stmt->execute()) {
        $success_message = "Password changed successfully!";
        
    } else {
        $errors[] = "Error updating password!";
    }
    
    $stmt->close();
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<title>Schedule an Appointment</title>

<link rel="shortcut icon" type="image/x-icon" href="/assets/img/tup-logo.png">
<link rel="stylesheet" href="/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/all.min.css">
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="/assets/css/feathericon.min.css">
<link rel="stylesheet" href="/assets/plugins/datatables/datatables.min.css">
<link rel="stylesheet" href="/assets/plugins/morris/morris.css">
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="/assets/css/select2.min.css">
<link rel="stylesheet" href="/assets/css/style.css">

<style>
    .btn-cardinal {
        background-color: #B31B1B; /* Cardinal Red */
        color: white;
    }
    .btn-cardinal:hover {
        background-color: #8B0000; /* Darker shade of Cardinal Red */
        color: white;
    }
    .form-control[readonly] {
        background-color: #f9f9f9;
    }
    .card-body {
        padding: 2rem;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .text-right {
        text-align: right;
    }
    .form-header {
        margin-bottom: 1.5rem;
    }
    
    .header {
            background-color: #c41e3c;
            margin-left: 5px;
        }
        
         .header-left {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .header-left a {
            display: flex;
            align-items: center;
        }

        .header-left img {
            margin-right: 10px;
        }
        
   @media (max-width: 576px) {
           
        }
        
         

    </style>

</head>

<body>
<div class="main-wrapper">
        <div class="header">
            <div class="header-left" style="background-color: #c41e3c; margin-left:-4.9px; margin-top:-1px;">
                <a>
                    <img src="/assets/img/logo.png" width="65" height="65" alt="" >
                </a>
            </div>
            <a href="javascript:void(0);" id="toggle_btn"> </a>
            <a class="mobile_btn" id="mobile_btn"> <i class="fas fa-bars"></i> </a>
            <ul class="nav user-menu" >
                <li class="nav-item dropdown has-arrow" >
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <span class="user-img"><img class="rounded-circle" src="/assets/img/user.png" width="31" alt=""></span>
                    </a>
                    <div class="dropdown-menu" style="left:-120px">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="/assets/img/user.png" alt="" class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></h6>
                                <p class="text-muted mb-0">Admin</p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="/admin/profile.php">My Profile</a>
                        <a class="dropdown-item" href="/index.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>

    <!-- /Header -->

 <!------SIDEBAR----->
 <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="active"> <a href="/admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a> </li>
                        <li class="list-divider"></li>
                        <li class="submenu"> <a href="#"><i class="fas fa-suitcase"></i> <span> Appointments </span> <span class="menu-arrow"></span></a>
                            <ul class="submenu_class" style="display: none;">
                                <li><a href="/admin/pending.appointment.php"> Pending Appointment </a></li>
                                <li><a href="/admin/all.appointment.php"> All Appointment </a></li>
                            </ul>
                        </li>
                        <li class=""> <a href="/admin/calendar.php"><i class="fas fa-calendar-alt"></i> <span> Calendar </span></a></li>
                        <li class="submenu"> <a href="#"><i class="fas fa-book"></i> <span> Documents</span> <span class="menu-arrow"></span></a>
                            <ul class="submenu_class" style="display: none;">
                                <li><a href="/admin/pending.documents.php"> Pending </a></li>
                                <li><a href="/admin/finish.documents.php"> Completed </a></li>
                            </ul>
                        </li>
                        <li> <a href="/admin/history.php"><i class="fas fa-table"></i> <span>History</span></a> </li>
                    </ul>
                </div>
            </div>
        </div>
    <!-- /Sidebar -->

    <div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header mt-5">
            <div class="row">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="profile-menu">
                    <ul class="nav nav-tabs nav-tabs-solid">
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#per_details_tab">About</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#password_tab">Password</a> </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div id="per_details_tab" class="tab-pane fade show active">
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
                                                        <label class="col-lg-3 col-form-label">Middle Name</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" name="middlename" value="<?php echo htmlspecialchars($middlename); ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">Last Name</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">Suffix</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" name="suffix" value="<?php echo htmlspecialchars($suffix); ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-6">
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">Email</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">Cellphone Number</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" name="cellphone" value="<?php echo htmlspecialchars($cellphone); ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">Username</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" name="tupv_id" value="<?php echo htmlspecialchars($username); ?>" readonly>
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

                <!-- Password Change Tab -->
                <div id="password_tab" class="tab-pane fade">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"></h4>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="form-group">
                                            <label>Old Password</label>
                                            <input type="password" class="form-control" name="old_password" id="old_password"required>
                                        </div>
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" class="form-control" name="new_password" id="new_password"  required>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" class="form-control" name="confirm_password" id="confirm_password"  required>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" name="change_password" class="btn btn-cardinal">Change Password</button>
                                        </div>
                                        <!-- Display error messages here -->
                                        <?php if (!empty($errors)): ?>
    <div class="alert alert-danger" style="margin-top: -400px;">
        <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($success_message)): ?>
    <div class="alert alert-success" style="margin-top: -400px;">
        <p><?php echo htmlspecialchars($success_message); ?></p>
    </div>
<?php endif; ?>



        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

	<script data-cfasync="false" src="../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
	<script src="/assets/js/jquery-3.5.1.min.js"></script>
	<script src="/assets/js/popper.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/moment.min.js"></script>
	<script src="/assets/js/select2.min.js"></script>
	<script src="/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="/assets/js/bootstrap-datetimepicker.min.js"></script>
	<script src="/assets/js/script.js"></script>

    
</body>

</html> 