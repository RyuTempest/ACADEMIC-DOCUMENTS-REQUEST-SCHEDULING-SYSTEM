<?php
include 'include/config.php';

session_start();

// Initialize messages
$success_message = '';
$error_message = '';
$errors = array(
    'username' => '',
    'password' => '',
    'general' => ''
);

if (isset($_POST['submit'])) {
    // Initialize variables with empty strings if not set
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate fields
    if (empty($username)) {
        $errors['username'] = 'Username is required!';
    }
    if (empty($password)) {
        $errors['password'] = 'Password is required!';
    }

    // If no errors, check credentials
    if (!array_filter($errors)) {
        if (!empty($username) && !empty($password)) {
            $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $role = $row['role']; 
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['role'] = $role;

                        // Check if the user has submitted the form
                        $sql = "SELECT COUNT(*) FROM user_submissions WHERE user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $row['id']);
                        $stmt->execute();
                        $stmt->bind_result($submission_count);
                        $stmt->fetch();
                        $stmt->close();

                        if ($role === 'admin') {
                            $success_message = 'Login successful! Redirecting to Admin Dashboard...';
                            header('Location: /admin/dashboard.php');
                            exit();
                        } elseif ($role === 'user') {
                            if ($submission_count > 0) {
                                $success_message = 'Login successful! Redirecting to User Calendar...';
                                header('Location: /user/up.form.appointment.php');
                            } else {
                                $success_message = 'Login successful! Redirecting to Form Submission Page...';
                                header('Location: /user/sub.form.appointment.php');
                            }
                            exit();
                        } else {
                            $errors['general'] = 'Invalid user role!';
                        }
                    } else {
                        $errors['general'] = 'Invalid username or password!';
                    }
                } else {
                    $errors['general'] = 'Invalid username or password!';
                }
            } else {
                $errors['general'] = 'Error executing query: ' . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADRS</title>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/tup-logo.avif">
    <style>
        /* Inline critical CSS */
        body {
            background: url('/assets/img/login.avif') no-repeat center center fixed;
            background-size: cover;
        }
        .fade-out {
            opacity: 1;
            transition: opacity 1s ease-out;
        }
        .fade-out.hidden {
            opacity: 0;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"></noscript>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-8 rounded-lg shadow-lg" style="background-color: #D9D9D9; opacity:90%;">
        <div class="flex flex-col items-center" >
            <img class="w-24 h-24 mb-4" src="/assets/img/tup-logo.png" alt="Logo">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 text-center">Document Access Module</h1>
            <p class="text-gray-600 text-center">TUP Visayas Request Scheduling</p>
        </div>
        <h2 class="text-2xl font-bold text-center text-gray-900">Login</h2>
        <?php if ($success_message): ?>
            <div id="success-message" class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg fade-out"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($errors['general']): ?>
            <div id="error-message" class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg fade-out"><?php echo htmlspecialchars($errors['general']); ?></div>
        <?php endif; ?>
        <form class="space-y-6" action="" method="POST">
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">TUPV ID</label>
                    <input id="username" name="username" type="text" required pattern="<?php echo ($_SESSION['role'] === 'user') ? 'TUPV-\d{2}-\d{4}' : '.*'; ?>" class="block w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <?php if (!empty($errors['username'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo htmlspecialchars($errors['username']); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mt-4 relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required class="block w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3C5.58 3 1.73 6.11.29 10c1.44 3.89 5.29 7 9.71 7s8.27-3.11 9.71-7C18.27 6.11 14.42 3 10 3zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                        </button>
                    </div>
                    <?php if (!empty($errors['password'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo htmlspecialchars($errors['password']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <button type="submit" name="submit" class="w-full px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Login</button>
            </div>
        </form>
        <div class="flex items-center justify-between">
            <div class="text-sm">
                <a href="/auth/forgot.password.php" class="font-medium text-red-600 hover:text-red-500">Forgot Password?</a>
            </div>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600">Don’t have an account? <a href="/auth/register.php" class="font-medium text-red-600 hover:text-red-500">Register</a></p>
        </div>
    </div>
    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var successMessage = document.getElementById('success-message');
                var errorMessage = document.getElementById('error-message');
                if (successMessage) {
                    successMessage.classList.add('hidden');
                }
                if (errorMessage) {
                    errorMessage.classList.add('hidden');
                }
            }, 1000);

            var togglePassword = document.getElementById('toggle-password');
            var passwordField = document.getElementById('password');
            var eyeIcon = document.getElementById('eye-icon');

            togglePassword.addEventListener('click', function() {
                var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                if (type === 'text') {
                    eyeIcon.setAttribute('d', 'M10 3C5.58 3 1.73 6.11.29 10c1.44 3.89 5.29 7 9.71 7s8.27-3.11 9.71-7C18.27 6.11 14.42 3 10 3zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z');
                } else {
                    eyeIcon.setAttribute('d', 'M10 3C5.58 3 1.73 6.11.29 10c1.44 3.89 5.29 7 9.71 7s8.27-3.11 9.71-7C18.27 6.11 14.42 3 10 3zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z');
                }
            });
        });
    </script>
</body>
</html>
