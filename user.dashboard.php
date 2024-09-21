<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT firstname, lastname FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname);
$stmt->fetch();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUPV Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/tup-logo.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .welcome-text {
            display: none;
            font-size: 1.5rem;
            margin-top: 20px;
        }
        .bg-cardinal-red {
            background-color: #C41E3A;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto">
        <header class="flex justify-between items-center py-4 mb-4 border-b border-gray-300">
            <a href="user.dashboard.php" class="flex items-center text-black no-underline">
                <img src="assets/img/tup-logo.png" alt="Logo" width="50" height="50" class="mr-2">
                <span class="text-2xl">TUPV</span>
            </a>
            <div class="flex items-center">
                <div class="relative mr-3">
                    <button class="flex items-center text-black focus:outline-none" id="dropdownUser1">
                        <img src="assets/img/user.png" alt="User" width="32" height="32" class="rounded-full">
                    </button>
                    <ul class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg hidden" id="dropdownMenu">
                        <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="profile.html">My Profile</a></li>
                        <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="login.php">Logout</a></li>
                    </ul>
                </div>
                <a href="#" class="text-black no-underline relative">
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-0 right-0 inline-block w-3 h-3 bg-cardinal-red rounded-full"></span>
                </a>
            </div>
        </header>

        <div class="flex flex-wrap">
            <nav id="sidebar" class="w-full md:w-1/4 lg:w-1/5 bg-white p-4 border-r border-gray-300">
                <ul class="space-y-2">
                    <li>
                        <a class="flex items-center text-black no-underline py-2 px-4 rounded hover:bg-gray-100" href="user.dashboard.php">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center text-black no-underline py-2 px-4 rounded hover:bg-gray-100" href="form.appointment.php">
                            <i class="fas fa-suitcase mr-2"></i>
                            Make an Appointment
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center text-black no-underline py-2 px-4 rounded hover:bg-gray-100" href="edit-booking.html">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            All Appointments
                        </a>
                    </li>
                </ul>
            </nav>

            <main class="w-full md:w-3/4 lg:w-4/5 p-4">
                <div class="flex justify-between items-center py-3 mb-3 border-b border-gray-300">
                    <h1 class="text-2xl">Good Morning <?php echo htmlspecialchars($firstname); ?></h1>
                </div>
                <button id="welcomeBtn" class="bg-cardinal-red text-white py-2 px-4 rounded">Click to Display Welcome Text</button>
                <div id="welcomeText" class="welcome-text">Welcome to your dashboard, <?php echo htmlspecialchars($firstname); ?>!</div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('welcomeBtn').addEventListener('click', function() {
            document.getElementById('welcomeText').style.display = 'block';
        });

        document.getElementById('dropdownUser1').addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
