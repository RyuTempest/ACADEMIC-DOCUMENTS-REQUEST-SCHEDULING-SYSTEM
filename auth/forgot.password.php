<?php
require '../include/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Function to send password reset email
function sendPasswordResetEmail($email, $token, $smtpEmail, $smtpPassword) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpEmail; // Your Gmail address
        $mail->Password   = $smtpPassword; // Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($smtpEmail, 'TUPV ADRS');
        $mail->addAddress($email); // Send the email to the user's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';

        // Password reset link
        $resetLink = "https://adrstupvregistrar.online/auth/reset.password.php?token=" . urlencode($token);
        $mail->Body = "
            Hello,<br><br>
            We received a request to reset your password. Click the link below to reset it:<br><br>
            <a href='$resetLink'>$resetLink</a><br><br>
            If you didn't request this, you can safely ignore this email.<br><br>
            Best regards,<br>TUPV ADRS
        ";

        $mail->send();
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Email Sent!',
                    text: 'Password reset email has been sent.',
                    confirmButtonColor: '#C41E3A'
                }).then(() => {
                    window.location.href = 'https://mail.google.com';
                });
              </script>";
    } catch (Exception $e) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}',
                    confirmButtonColor: '#C41E3A'
                });
              </script>";
    }
}

// Handling the form submission
if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email address exists in the database
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        // Generate a random password reset token
        $token = bin2hex(random_bytes(16));

        // Update the user's password reset token in the database
        $stmt = $conn->prepare("UPDATE user SET password_reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Send a password reset email to the user
        $smtpEmail = 'adrs7208@gmail.com'; // Replace with your Gmail address
        $smtpPassword = 'nncr smzs musy hiqk'; // Replace with your Gmail app password
        sendPasswordResetEmail($email, $token, $smtpEmail, $smtpPassword);
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Email Not Found!',
                    text: 'The email address you entered is not registered.',
                    confirmButtonColor: '#C41E3A'
                });
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/tup-logo.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
<body class="flex items-center justify-center min-h-screen" style="background-color: #D9D9D9; opacity:80%;">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow-lg">
        <div class="flex flex-col items-center">
            <img class="w-24 h-24 mb-4" src="/assets/img/tup-logo.png" alt="Logo">
            <h1 class="text-3xl font-bold text-gray-900">Forgot Password</h1>
            <p class="text-gray-600">Enter your email address to reset your password.</p>
        </div>
        <form class="space-y-6" action="" method="POST">
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input id="email" name="email" type="email" required class="block w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>
            <div>
                <button href="/index.php" type="submit" name="submit" class="w-full px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Reset Password</button>
            </div>
        </form>
    </div>
</body>
</html>
