<?php
session_start();
include '../include/config.php';

// Redirect if not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's name
$sql = "SELECT firstname, lastname FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname);
$stmt->fetch();
$stmt->close();

// Fetch all users
function getAllUsers($conn) {
    $sql = "SELECT id, firstname, middlename, lastname, suffix, email, cellphone, username FROM user ORDER BY id ASC"; 

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $users;
}

// Fetch all users
$users = getAllUsers($conn);



// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>All Users</title>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/logo.png">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables/datatables.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
<style>
        .img {
            background: url("/assets/img/logo.png");
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

        .col {
            margin-top: 50px;
        }

        .card-title {
            font-size: 30px;
        }

        .page-wrapper {
            background: url("/assets/img/dashboard6.svg");
            background-size: cover;
        }

        .header {
            background-color: #c41e3c;
            margin-left: 5px;
        }

        .header .has-arrow .dropdown-toggle:after {
            border-bottom: 2px solid #ffffff;
            border-right: 2px solid #ffffff;
        }

        @media (max-width: 768px) {
            /* Add responsive styles if needed */
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="header">
            <div class="header-left" style="background-color: #c41e3c; margin-left:-4.9px; margin-top:-1px;">
                <a>
                    <img src="/assets/img/logo.png" width="65" height="65" alt="">
                </a>
            </div>
            <a href="javascript:void(0);" id="toggle_btn"></a>
            <a class="mobile_btn" id="mobile_btn"><i class="fas fa-bars"></i></a>
            <ul class="nav user-menu">
                <li class="nav-item dropdown has-arrow">
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
                        <li> <a href="/admin/history.php"><i class="fas fa-table"></i> <span>History</span></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title float-left mt-2">All Users</h4>
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
                                                <th>Username</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Cellphone</th>

                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($users) > 0): ?>
                                                <?php foreach ($users as $user): ?>
                                                    <tr>
                                                         <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                      
                                                        <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['middlename'] . ' ' . $user['lastname'] . ' ' . $user['suffix']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['cellphone']); ?></td>
                                                       
                                                        
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">No users found.</td>
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

    <script src="/assets/js/jquery-3.5.1.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables/datatables.min.js"></script>
    <script src="/assets/js/script.js"></script>
   
    <script>
  $(document).ready(function() {
    // Check if DataTable has already been initialized and destroy it
    if ($.fn.DataTable.isDataTable('.datatable')) {
        $('.datatable').DataTable().destroy();
    }

    // Initialize DataTable
    $('.datatable').DataTable({
        "order": [[0, "desc"]] // Ensure the first column (ID) is sorted in descending order
    });
});
    </script>
</body>
</html>
