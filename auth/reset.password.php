<?php
include '../include/config.php';

if (isset($_POST['submit'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $token = $_GET['token'];

    // Check if the password and confirm password match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {
        // Check if the token is valid
        $stmt = $conn->prepare("SELECT * FROM user WHERE password_reset_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Update the user's password
            $stmt = $conn->prepare("UPDATE user SET password = ? WHERE password_reset_token = ?");
            $stmt->bind_param("ss", $hashedPassword, $token);
            $stmt->execute();
        

            // Remove the password reset token
            $stmt = $conn->prepare("UPDATE user SET password_reset_token = NULL WHERE password_reset_token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $success_message = "Password reset successfully!";
               // Redirect to login page
               header('Location: /index.php');
               exit(); // Ensure script stops executing after redirect
        } else {
            echo "Invalid token!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/tup-logo.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <style>
        @keyframes backgroundColorChange {
            0% { background-color: #C41E3A; } /* Cardinal Red */
            33% { background-color: #A31621; } /* Darker Cardinal Red */
            66% { background-color: #E63946; } /* Lighter Cardinal Red */
            100% { background-color: #C41E3A; } /* Cardinal Red */
        }

        body {
            background: url('/assets/img/login.svg') no-repeat center center fixed;
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
</head>
<body class="flex items-center justify-center min-h-screen " style="background-color: #D9D9D9; opacity:80%;">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow-lg">
        <div class="flex flex-col items-center">
            <img class="w-24 h-24 mb-4" src="/assets/img/tup-logo.png" alt="Logo">
            <h1 class="text-3xl font-bold text-gray-900">Reset Password</h1>
            <p class="text-gray-600">Enter your new password.</p>
        </div>
        <form class="space-y-6" action="" method="POST">
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input id="password" name="password" type="password" required class="block w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" required class="block w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>
            <div>
                <button  type="submit" name="submit" class="w-full px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Reset Password</button>
            </div>
        </form>
    </div>
</body>
</html>