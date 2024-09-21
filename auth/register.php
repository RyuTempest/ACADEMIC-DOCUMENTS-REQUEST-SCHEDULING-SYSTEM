<?php
include '../include/config.php';

// Initialize messages
$success_message = '';
$error_message = '';
$errors = array(
    'firstname' => '',
    'lastname' => '',
    'email' => '',
    'cellphone' => '',
    'username' => '',
    'password' => '',
    'confirm_password' => '',
    'general' => ''
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input values
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $cellphone = trim($_POST['cellphone']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmpassword'];

    // Validate input fields
    if (empty($firstname)) {
        $errors['firstname'] = 'Firstname is required!';
    }
    if (empty($lastname)) {
        $errors['lastname'] = 'Lastname is required!';
    }
    if (empty($email)) {
        $errors['email'] = 'Email is required!';
    }
    if (empty($cellphone)) {
        $errors['cellphone'] = 'Cellphone number is required!';
    } elseif (!preg_match('/^\d{11}$/', $cellphone)) {
        $errors['cellphone'] = 'Cellphone Number must be exactly 11 digits!';
    }
    if (empty($username)) {
        $errors['username'] = 'Username is required!';
    }
    if (empty($password)) {
        $errors['password'] = 'Password is required!';
    }
    if (empty($confirm_password)) {
        $errors['confirm_password'] = 'Please confirm your password!';
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match!';
    }

    // Check for duplicates if there are no errors yet
    if (!array_filter($errors)) {
        $select = "SELECT * FROM user WHERE email = ? OR username = ? OR cellphone = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("sss", $email, $username, $cellphone);
        $stmt->execute();
        $result = $stmt->get_result();
        $existing_user = $result->fetch_assoc();

        if ($existing_user) {
            if ($existing_user['email'] == $email) {
                $errors['email'] = 'Email already exists!';
            }
            if ($existing_user['username'] == $username) {
                $errors['username'] = 'Username already exists!';
            }
            if ($existing_user['cellphone'] == $cellphone) {
                $errors['cellphone'] = 'Cellphone number already exists!';
            }
        }

        // If no errors, insert new user into the database
        if (!array_filter($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO user (firstname, lastname, email, cellphone, username, password) 
                       VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("ssssss", $firstname, $lastname, $email, $cellphone, $username, $hashed_password);

            if ($stmt->execute()) {
                $success_message = 'Registration successful! You can now log in.';
                header('location:/book/auth/login.php');
                exit();
            } else {
                $error_message = 'Failed to register. MySQL error: ' . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Registration</title>
    <link rel="shortcut icon" type="image/x-icon" href="/book/assets/img/tup-logo.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: url('/book/assets/img/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }
        .form-label {
            font-weight: bold;
            color: #555;
        }
        .form-input {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 0.75rem;
            width: 100%;
            margin-top: 0.5rem;
        }
        .form-input:focus {
            border-color: #C41E3A;
            outline: none;
            box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.2);
        }
        .form-button {
            background-color: #C41E3A;
            color: white;
            padding: 0.75rem;
            border-radius: 5px;
            width: 100%;
            font-weight: bold;
            margin-top: 1rem;
        }
        .form-button:hover {
            background-color: #A31621;
        }
        .form-error {
            color: #C41E3A;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100 bg-opacity-75">
    <div class="w-full max-w-3xl form-container">
        <div class="text-center">
            <img class="w-24 mx-auto mb-4" src="/book/assets/img/tup-logo.png" alt="Logo">
            <h1 class="form-title">Register</h1>
        </div>
        <!-- Display Success or Error Message -->
        <?php if (!empty($success_message)) : ?>
            <div class="p-4 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php elseif (!empty($error_message)) : ?>
            <div class="p-4 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label for="firstname" class="form-label">Firstname</label>
                    <input class="form-input" type="text" name="firstname" value="<?php echo htmlspecialchars($firstname ?? ''); ?>" placeholder="Firstname" required>
                    <?php if (!empty($errors['firstname'])) echo '<small class="form-error">' . $errors['firstname'] . '</small>'; ?>
                </div>

                <div class="space-y-1">
                    <label for="lastname" class="form-label">Lastname</label>
                    <input class="form-input" type="text" name="lastname" value="<?php echo htmlspecialchars($lastname ?? ''); ?>" placeholder="Lastname" required>
                    <?php if (!empty($errors['lastname'])) echo '<small class="form-error">' . $errors['lastname'] . '</small>'; ?>
                </div>

                <div class="space-y-1">
                    <label for="email" class="form-label">Email</label>
                    <input class="form-input" type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" placeholder="Email" required>
                    <?php if (!empty($errors['email'])) echo '<small class="form-error">' . $errors['email'] . '</small>'; ?>
                </div>

                <div class="space-y-1">
                    <label for="cellphone" class="form-label">Cellphone Number</label>
                    <input class="form-input" type="text" name="cellphone" value="<?php echo htmlspecialchars($cellphone ?? ''); ?>" placeholder="Cellphone Number" required>
                    <?php if (!empty($errors['cellphone'])) echo '<small class="form-error">' . $errors['cellphone'] . '</small>'; ?>
                </div>

                <div class="space-y-1">
                    <label for="username" class="form-label">TUPV ID Number</label>
                    <input class="form-input" type="text" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" placeholder="Username" required>
                    <?php if (!empty($errors['username'])) echo '<small class="form-error">' . $errors['username'] . '</small>'; ?>
                </div>

                <div class="space-y-1 relative">
                    <label for="password" class="form-label">Password</label>
                    <input class="form-input" type="password" name="password" id="password" placeholder="Password" required>
                    <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 3C5.58 3 1.73 6.11.29 10c1.44 3.89 5.29 7 9.71 7s8.27-3.11 9.71-7C18.27 6.11 14.42 3 10 3zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </button>
                    <?php if (!empty($errors['password'])) echo '<small class="form-error">' . $errors['password'] . '</small>'; ?>
                </div>

                <div class="space-y-1 relative">
                    <label for="confirmpassword" class="form-label">Confirm Password</label>
                    <input class="form-input" type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password" required>
                    <button type="button" id="toggle-confirmpassword" class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600">
                        <svg id="eye-icon-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 3C5.58 3 1.73 6.11.29 10c1.44 3.89 5.29 7 9.71 7s8.27-3.11 9.71-7C18.27 6.11 14.42 3 10 3zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </button>
                    <?php if (!empty($errors['confirm_password'])) echo '<small class="form-error">' . $errors['confirm_password'] . '</small>'; ?>
                </div>
            </div>

            <button class="form-button" type="submit" name="submit">Register</button>
        </form>

        <div class="flex items-center justify-center mt-6 space-x-2">
            <span class="text-gray-600">or</span>
        </div>

        <div class="mt-6 text-center">
            <span class="text-gray-600">Already have an account? <a href="login.php" class="text-red-600 hover:text-red-700">Login</a></span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var togglePassword = document.getElementById('toggle-password');
            var passwordField = document.getElementById('password');
            var eyeIcon = document.getElementById('eye-icon');

            togglePassword.addEventListener('click', function() {
                var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
            });

            var toggleConfirmPassword = document.getElementById('toggle-confirmpassword');
            var confirmPasswordField = document.getElementById('confirmpassword');
            var eyeIconConfirm = document.getElementById('eye-icon-confirm');

            toggleConfirmPassword.addEventListener('click', function() {
                var type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordField.setAttribute('type', type);
            });
        });
    </script>
</body>

</html>
